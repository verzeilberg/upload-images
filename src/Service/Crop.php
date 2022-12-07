<?php

namespace verzeilberg\UploadImagesBundle\Service;


class Crop
{
    /**
     * @param null $sOriLocation
     * @param null $sDestinationFolder
     * @param null $iImgWidth
     * @param null $iImgHeight
     * @param string $imageType
     * @param null $imageObject
     * @return string
     */
    public function resizeAndCropImage($sOriLocation = null, $sDestinationFolder = null, $iImgWidth = null, $iImgHeight = null)
    {
        $sPathParts = pathinfo($sOriLocation);
        $sFileName = $sPathParts['basename'];
        $sMimeType = mime_content_type($sOriLocation);

        // Depending on wich file type is uploaded create a image
        if ($sMimeType == "image/jpeg") {
            $oSourceImage = imagecreatefromjpeg($sOriLocation);
        } else if ($sMimeType == "image/png") {
            $oSourceImage = imagecreatefrompng($sOriLocation);
        } else if ($sMimeType == "image/gif") {
            $oSourceImage = imagecreatefromgif($sOriLocation);
        } else {
            return 'The file is not a image';
        }

        // Get the widht and height of the uploade image
        $aFileProps = getimagesize($sOriLocation);

        $iWidth = $aFileProps[0];
        $iHeight = $aFileProps[1];

        $original_aspect = $iWidth / $iHeight;
        $thumb_aspect = $iImgWidth / $iImgHeight;

        if ($original_aspect >= $thumb_aspect) {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $iImgHeight;
            $new_width = $iWidth / ($iHeight / $iImgHeight);
        } else {
            // If the thumbnail is wider than the image
            $new_width = $iImgWidth;
            $new_height = $iHeight / ($iWidth / $iImgWidth);
        }

        # Create Temporary image with new Width and height
        # iNewWidth -> integer
        # iNewHeight -> integer
        $oTempImage = imagecreatetruecolor($iImgWidth, $iImgHeight);


        if ($sMimeType == "image/png" || $sMimeType == "image/gif") {

            $oTransparentIndex = imagecolortransparent($oSourceImage);
            if ($oTransparentIndex >= 0) { // GIF
                imagepalettecopy($oSourceImage, $oTempImage);
                imagefill($oTempImage, 0, 0, $oTransparentIndex);
                imagecolortransparent($oTempImage, $oTransparentIndex);
                imagetruecolortopalette($oTempImage, true, 256);
            } else { // PNG
                imagealphablending($oTempImage, false);
                imagesavealpha($oTempImage, true);
                $oTransparent = imagecolorallocatealpha($oTempImage, 255, 255, 255, 127);
                imagefilledrectangle($oTempImage, 0, 0, $iImgWidth, $iImgHeight, $oTransparent);
            }
        }

        // Resize and Crop
        imagecopyresampled($oTempImage, $oSourceImage, 0 - ($new_width - $iImgWidth) / 2, 0 - ($new_height - $iImgHeight) / 2, 0, 0, $new_width, $new_height, $iWidth, $iHeight);

        $sPathToFile = $sDestinationFolder . '/' . $sFileName;


        //Check MimeType to create image
        if ($sMimeType == "image/jpeg") {
            imagejpeg($oTempImage, $sPathToFile, 80);
        } else if ($sMimeType == "image/png") {
            imagepng($oTempImage, $sPathToFile, 9);
        } else if ($sMimeType == "image/gif") {
            imagegif($oTempImage, $sPathToFile);
        } else {
            return 'Image could not be resized';
        }
        imagedestroy($oSourceImage);
        imagedestroy($oTempImage);

        //Create Image type
        return true;

    }

    public function CropImage($srcFile = null, $dstFile = null, $x, $y, $w, $h, $dw, $dh, $img_quality = 90)
    {

        // Check if file exist
        if (!file_exists($srcFile)) {
            return 'Could not find the original image';
        }

        // Check if the destionfolder is set. When false then Original location becomes Destination folder
        if ($dstFile == null) {
            $dstFile = dirname($srcFile);
        }

        // Check if the destination folder exist
        //if (!is_dir($dstFile)) {
        //return 'Folder ' . $dstFile . ' does not exist';
        // }

        /**
         * Check if folder exists and has the appropriate rights otherwise create and give rights
         */
        if (!file_exists($dstFile)) {
            mkdir($dstFile, 0777, true);
        } elseif (!is_writable($dstFile)) {
            chmod($dstFile, 0777);
        }

        //get file info like basename and mime type of the file
        $sPathParts = pathinfo($srcFile);
        $sFileName = $sPathParts['basename'];
        $sMimeType = mime_content_type($srcFile);

        // Switch between jpg, png or gif
        switch ($sMimeType) {
            case "image/jpeg":
                $img_r = imagecreatefromjpeg($srcFile);
                $dst_r = imagecreatetruecolor($dw, $dh);
                imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $dw, $dh, $w, $h);
                imagejpeg($dst_r, $dstFile . $sFileName, $img_quality);
                break;
            case "image/png":

                $dst_r = imagecreatetruecolor($dw, $dh);
                $img_r = imagecreatefrompng($srcFile);
                $alpha_channel = imagecolorallocatealpha($img_r, 0, 0, 0, 127);
                $oTransparentIndex = imagecolortransparent($img_r);
                imagealphablending($dst_r, false);
                imagesavealpha($dst_r, true);
                $oTransparent = imagecolorallocatealpha($dst_r, 255, 255, 255, 127);
                imagefilledrectangle($dst_r, 0, 0, $dw, $dh, $oTransparent);


                imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $dw, $dh, $w, $h);
                imagepng($dst_r, $dstFile . $sFileName, 9);
                break;
            case "image/gif":
                $img_r = imagecreatefromgif($srcFile);
                $dst_r = ImageCreateTrueColor($dw, $dh);

                $oTransparentIndex = imagecolortransparent($srcFile);
                imagepalettecopy($srcFile, $dst_r);
                imagefill($dst_r, 0, 0, $oTransparentIndex);
                imagecolortransparent($dst_r, $oTransparentIndex);
                imagetruecolortopalette($dst_r, true, 256);

                imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $dw, $dh, $w, $h);
                imagegif($dst_r, $dstFile . $sFileName);
                break;
        }

        return true;
    }


}