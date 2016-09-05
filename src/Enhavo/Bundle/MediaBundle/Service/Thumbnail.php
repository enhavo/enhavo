<?php

namespace Enhavo\Bundle\MediaBundle\Service;

class Thumbnail
{

    static public function make($input, $output, $width, $height, $quality = 80)
    {
        $size = getimagesize( $input );
        $oldWidth = $size[ 0 ];
        $oldHeight = $size[ 1 ];

        if($oldWidth > $oldHeight)
        {
            //landscape
            $ratioOld = $oldWidth/$oldHeight;
            $ratioNew = $width/$height;
            if($ratioNew <= $ratioOld) {
                $factor = $oldHeight/$height;
                $widthRelative = $width*$factor;
                $halfOfRelativeWidth = $widthRelative/2;

                $src_x = ($oldWidth/2)-$halfOfRelativeWidth;
                $src_y = 0;

                $src_w = $widthRelative;
                $src_h = $oldHeight;
            } else {
                $factor = $oldWidth/$width;
                $heightRelative = $height*$factor;
                $halfOfRelativeHeight = $heightRelative/2;

                $src_x = 0;
                $src_y = ($oldHeight/2)-$halfOfRelativeHeight;

                $src_h = $heightRelative;
                $src_w = $oldWidth;
            }
            $dst_x = 0;
            $dst_y = 0;

            $dst_w = $width;
            $dst_h = $height;
        } else {
            //portrait
            $factor = $oldWidth/$width;
            $heightRelative = $height*$factor;
            $halfOfRelativeHeight = $heightRelative/2;

            $src_x = 0;
            $src_y = ($oldHeight/2)-$halfOfRelativeHeight;

            $dst_x = 0;
            $dst_y = 0;

            $dst_w = $width;
            $dst_h = $height;

            $src_w = $oldWidth;
            $src_h = $heightRelative;
        }

        switch( $size[ 2 ] )
        {
            case( 1 ):
                // GIF
                exec("convert -version", $out, $rcode);
                if($rcode === 0 && self::isGifAnimated($input)) {
                    $resize_dst_w = max($dst_w,$dst_h);
                    $resize_dst_h = max($dst_w,$dst_h);
                    exec("convert ".$input." -size ".$src_w."x".$src_h." -coalesce -resize ".$resize_dst_w."x".$resize_dst_h." ".$output);
                    $oldRatio = $oldWidth/$oldHeight;
                    $newRatio = $dst_w/$dst_h;
                    if($oldRatio > $newRatio) {
                        $dst_x = abs($dst_w-$resize_dst_w)/2;
                    } else {
                        $dst_y = abs($dst_h-$resize_dst_h)/2;
                    }
                    exec("convert ".$output." -repage 0x0 -crop ".$dst_w."x".$dst_h."+".$dst_x."+".$dst_y." +repage ".$output);
                    return true;
                    break;
                } else {
                    $src_image = imageCreateFromGIF( $input );
                    $dst_image = imageCreateTrueColor( $dst_w, $dst_h );

                    imagecolortransparent($dst_image, imagecolorallocatealpha($dst_image, 0, 0, 0, 127));
                    imagealphablending($dst_image, false);
                    imagesavealpha($dst_image, true);

                    imagecopyresampled ($dst_image, $src_image , $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h);
                    imageGIF( $dst_image, $output );
                    unset($dst_image);
                    unset($src_image);
                    return true;
                    break;
                }
            case( 2 ):
                // JPEG
                $src_image = imageCreateFromJPEG( $input );
                $dst_image = imageCreateTrueColor( $width, $height );
                imagecopyresampled ($dst_image, $src_image , $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h);
                imageJPEG( $dst_image, $output, $quality);
                unset($dst_image);
                unset($src_image);
                return true;
                break;
            case( 3 ):
                // PNG
                $src_image = imageCreateFromPNG( $input );
                $dst_image = imageCreateTrueColor( $width, $height );

                imagecolortransparent($dst_image, imagecolorallocatealpha($dst_image, 0, 0, 0, 127));
                imagealphablending($dst_image, false);
                imagesavealpha($dst_image, true);

                imagecopyresampled ($dst_image, $src_image , $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h);
                imagePNG( $dst_image, $output, 0);
                unset($dst_image);
                unset($src_image);
                return true;
                break;
            default:
                return false;
        }
    }

    static public function isGifAnimated($filepath) {
        $filecontents = file_get_contents($filepath);

        $str_loc = 0;
        $count = 0;

        while ($count < 2) {

            $where1 = strpos($filecontents,"\x00\x21\xF9\x04",$str_loc);

            if ($where1 === FALSE) {
                break;
            } else {
                $str_loc = $where1+1;
                $where2 = strpos($filecontents,"\x00\x2C",$str_loc);
                if ($where2 === FALSE) {
                    break;
                } else {
                    if ($where1+8 == $where2) {
                        $count++;
                    }
                    $str_loc = $where2+1;
                }
            }
        }

        return $count > 1;
    }
}