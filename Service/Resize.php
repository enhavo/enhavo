<?php

namespace esperanto\MediaBundle\Service;

class Resize
{

    static public function make($inputFile, $outputFile, $maxWidth, $maxHeight)
    {
        $size = getimagesize( $inputFile );
        $oldWidth = $size[ 0 ];
        $oldHeight = $size[ 1 ];

        if($oldWidth > $oldHeight)
        {
            //landscape
            if($oldWidth > $maxWidth)
            {
                $factor = $maxWidth/$oldWidth;
                $newHeight = $oldHeight*$factor;

                $dst_w = $maxWidth;
                $dst_h = $newHeight;
            } else {
                copy($inputFile, $outputFile);
                return;
            }
        } else {
            //portrait
            if($oldHeight > $maxHeight)
            {
                $factor = $maxHeight/$oldHeight;
                $newWidth = $oldWidth*$factor;

                $dst_w = $newWidth;
                $dst_h = $maxHeight;
            } else {
                copy($inputFile, $outputFile);
                return;
            }
        }

        $src_x = 0;
        $src_y = 0;

        $dst_x = 0;
        $dst_y = 0;

        $src_w = $oldWidth;
        $src_h = $oldHeight;

        switch( $size[ 2 ] )
        {
            case( 1 ):
                // GIF
                $src_image = imageCreateFromGIF( $inputFile );
                $dst_image = imageCreateTrueColor( $dst_w, $dst_h );
                imagecopyresampled ($dst_image, $src_image , $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h);
                imageGIF( $dst_image, $outputFile );
                unset($dst_image);
                unset($src_image);
                return true;
                break;
            case( 2 ):
                // JPEG
                $src_image = imageCreateFromJPEG( $inputFile );
                $dst_image = imageCreateTrueColor( $dst_w, $dst_h );
                imagecopyresampled ($dst_image, $src_image , $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h);
                imageJPEG( $dst_image, $outputFile, 80);
                unset($dst_image);
                unset($src_image);
                return true;
                break;
            case( 3 ):
                // PNG
                $src_image = imageCreateFromPNG( $inputFile );
                $dst_image = imageCreateTrueColor( $dst_w, $dst_h );
                imagecopyresampled ($dst_image, $src_image , $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h);
                imagePNG( $dst_image, $outputFile, 0);
                unset($dst_image);
                unset($src_image);
                return true;
                break;
            default:
                return false;
        }
    }
}