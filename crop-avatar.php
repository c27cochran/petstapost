<?php

  session_start();

  ini_set('memory_limit', '512M');

  class CropAvatar {
    private $src;
    private $data;
    private $file;
    private $dst;
    private $type;
    private $extension;
    private $srcDir = 'img/upload';
    private $dstDir = 'img/avatar';
    private $msg;

    function __construct($src, $data, $file) {
      $this -> setSrc($src);
      $this -> setData($data);
      $this -> setFile($file);
      $this -> crop($this -> src, $this -> dst, $this -> data);
    }

    private function setSrc($src) {
      if (!empty($src)) {
        $type = exif_imagetype($src);

        if ($type) {
          $this -> src = $src;
          $this -> type = $type;
          $this -> extension = image_type_to_extension($type);
          $this -> setDst();
        }
      }
    }

    private function setData($data) {
      if (!empty($data)) {
        $this -> data = json_decode(stripslashes($data));
      }
    }

    private function setFile($file) {
      $errorCode = $file['error'];

      if ($errorCode === UPLOAD_ERR_OK) {
        $type = exif_imagetype($file['tmp_name']);

        if ($type) {
          $dir = $this -> srcDir;

          if (!file_exists($dir)) {
            mkdir($dir, 0777);
          }

          $extension = image_type_to_extension($type);
          $src = $dir . '/' . $_POST['username'] . '-avatar' . $extension;

          if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {

            if (file_exists($src)) {
              unlink($src);
            }

            $result = move_uploaded_file($file['tmp_name'], $src);

            if ($result) {
              $this -> src = $src;
              $this -> type = $type;
              $this -> extension = $extension;
              $this -> setDst();
            } else {
               $this -> msg = 'Failed to save file';
            }
          } else {
            $this -> msg = 'Please upload image with the following types: JPG, PNG, GIF';
          }
        } else {
          $this -> msg = 'Please upload image file';
        }
      } else {
        $this -> msg = $this -> codeToMessage($errorCode);
      }
    }

    private function setDst() {
      $dir = $this -> dstDir;

      if (!file_exists($dir)) {
        mkdir($dir, 0777);
      }

      if (file_exists($dir . '/' . $_POST['username'] . '-avatar' . '.png')) {
        unlink($dir . '/' . $_POST['username'] . '-avatar' . '.png');
      }

      if (!empty($_SESSION['profile_img_url'])) {
          if (file_exists($_SESSION['profile_img_url'])) {
              unlink($_SESSION['profile_img_url']);
          }
      }

      $this -> dst = $dir . '/' . $_POST['username'] . '-avatar' . '.png';
    }

    private function crop($src, $dst, $data) {
      if (!empty($src) && !empty($dst) && !empty($data)) {
        
        if (empty($exif['Orientation'])) {
          switch ($this -> type) {
            case IMAGETYPE_GIF:
              $src_img = imagecreatefromgif($src);
              break;

            case IMAGETYPE_JPEG:
              $src_img = imagecreatefromjpeg($src);
              break;

            case IMAGETYPE_PNG:
              $src_img = imagecreatefrompng($src);
              break;
          }

          $src_x = $data -> x;
          $src_y = $data -> y;
        }

        if (!$src_img) {
          $this -> msg = "Failed to read the image file";
          return;
        }

        $size = getimagesize($src);
        $size_w = $size[0]; // natural width
        $size_h = $size[1]; // natural height

        $src_img_w = $size_w;
        $src_img_h = $size_h;

        $exif = exif_read_data($src);

        if (!empty($exif['Orientation'])) {

         switch ($exif['FileType']) {
          case IMAGETYPE_GIF:
             $src_img = imagecreatefromgif($src);
             break;

           case IMAGETYPE_JPEG:
             $src_img = imagecreatefromjpeg($src);
             break;

           case IMAGETYPE_PNG:
             $src_img = imagecreatefrompng($src);
             break;
         }

         switch ($exif['Orientation']) {
           case 8:
             $new_img = imagerotate($src_img, 90, 0, imagecolorallocatealpha($src_img, 0, 0, 0, 127) );
             imagedestroy($src_img);
             $src_img = $new_img;
             $degrees = 90;
             break;
           case 3:
             $new_img = imagerotate($src_img, 180, 0, imagecolorallocatealpha($src_img, 0, 0, 0, 127) );
             imagedestroy($src_img);
             $src_img = $new_img;
             $degrees = 180;
             break;
           case 6:
             $new_img = imagerotate($src_img, -90, 0, imagecolorallocatealpha($src_img, 0, 0, 0, 127) );
             imagedestroy($src_img);
             $src_img = $new_img;
             $degrees = -90;
             break;
         }

            $src_x = $data -> x;
            $src_y = $data -> y;

            // Rotate the source image
            $deg = abs($degrees) % 180;
            $arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
            $src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
            $src_img_h = $size_w * sin($arc) + $size_h * cos($arc);
            // Fix rotated image miss 1px issue when degrees < 0
            $src_img_w -= 1;
            $src_img_h -= 1;
        }

        $tmp_img_w = $data -> width;
        $tmp_img_h = $data -> height;
        $dst_img_w = 220;
        $dst_img_h = 220;

        if ($src_x <= -$tmp_img_w || $src_x > $src_img_w) {
          $src_x = $src_w = $dst_x = $dst_w = 0;
        } else if ($src_x <= 0) {
          $dst_x = -$src_x;
          $src_x = 0;
          $src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
        } else if ($src_x <= $src_img_w) {
          $dst_x = 0;
          $src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
        }

        if ($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h) {
          $src_y = $src_h = $dst_y = $dst_h = 0;
        } else if ($src_y <= 0) {
          $dst_y = -$src_y;
          $src_y = 0;
          $src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
        } else if ($src_y <= $src_img_h) {
          $dst_y = 0;
          $src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
        }

        // Scale to destination position and size
        $ratio = $tmp_img_w / $dst_img_w;
        $dst_x /= $ratio;
        $dst_y /= $ratio;
        $dst_w /= $ratio;
        $dst_h /= $ratio;

        $dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);

        // Add transparent background to destination image
        imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
        imagesavealpha($dst_img, true);

        $result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

        if ($result) {
          if (!imagepng($dst_img, $dst)) {
            $this -> msg = "Failed to save the cropped image file";
          }
        } else {
          $this -> msg = "Failed to crop the image file";
        }

        imagedestroy($src_img);
        imagedestroy($dst_img);
      }
    }

    private function codeToMessage($code) {
      switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
          $message = 'The uploaded file exceeds the max filesize limit.';
          break;

        case UPLOAD_ERR_FORM_SIZE:
          $message = 'The uploaded file exceeds the max filesize limit.';
          break;

        case UPLOAD_ERR_PARTIAL:
          $message = 'The uploaded file was only partially uploaded. Please try again.';
          break;

        case UPLOAD_ERR_NO_FILE:
          $message = 'No file was uploaded.';
          break;

        case UPLOAD_ERR_NO_TMP_DIR:
          $message = 'Missing a temporary folder';
          break;

        case UPLOAD_ERR_CANT_WRITE:
          $message = 'Failed to write file to disk';
          break;

        case UPLOAD_ERR_EXTENSION:
          $message = 'File upload stopped by extension';
          break;

        default:
          $message = 'Unknown upload error';
      }

      return $message;
    }

    public function getResult() {
      return !empty($this -> data) ? $this -> dst : $this -> src;
    }

    public function getMsg() {
      return $this -> msg;
    }
  }

  $crop = new CropAvatar($_POST['avatar_src'], $_POST['avatar_data'], $_FILES['avatar_file']);

  $_SESSION['profile_img_url'] = $crop -> getResult();

  $response = array(
    'state'  => 200,
    'message' => $crop -> getMsg(),
    'result' => $_SESSION['profile_img_url']
  );

  echo json_encode($response);

?>
