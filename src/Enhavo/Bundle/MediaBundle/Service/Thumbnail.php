<?php

namespace enhavo\MediaBundle\Service;

class Thumbnail
{

    static public function make($input, $output, $width, $height)
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
                $src_image = imageCreateFromGIF( $input );
                $dst_image = imageCreateTrueColor( $width, $height );
                imagecopyresampled ($dst_image, $src_image , $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h);
                imageGIF( $dst_image, $output );
                unset($dst_image);
                unset($src_image);
                return true;
                break;
            case( 2 ):
                // JPEG
                $src_image = imageCreateFromJPEG( $input );
                $dst_image = imageCreateTrueColor( $width, $height );
                imagecopyresampled ($dst_image, $src_image , $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h);
                imageJPEG( $dst_image, $output, 80);
                unset($dst_image);
                unset($src_image);
                return true;
                break;
            case( 3 ):
                // PNG
                $src_image = imageCreateFromPNG( $input );
                $dst_image = imageCreateTrueColor( $width, $height );
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
}
