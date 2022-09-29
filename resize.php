<?php

/*
 * Вариант реализации 1.
 */
class ResizeImage
{
    private string $extension;

    private GdImage $image;
    private GdImage $newImage;

    private int $originalWidth;
    private int $originalHeight;

    private int $resizeWidth;
    private int $resizeHeight;

    public function __construct(string $filename)
    {
        if (file_exists($filename)) {
            $this->setImage($filename);
        } else {
            throw new Exception('Файл изображения не найден');
        }
    }


    private function setImage(string $filename): void
    {
        $size = getimagesize($filename);
        $this->extension = $size['mime'];

        $this->image = match ($this->extension) {
            'image/jpg', 'image/jpeg' => imagecreatefromjpeg($filename),
            'image/gif' => imagecreatefromgif($filename),
            'image/png' => imagecreatefrompng($filename),
            default => throw new Exception('Файл не является допустимым изображением'),
        };

        $this->originalWidth = imagesx($this->image);
        $this->originalHeight = imagesy($this->image);
    }


    public function render(): void
    {
        switch ($this->extension) {
            case 'image/jpg':
            case 'image/jpeg':
                header('Content-Type: image/jpeg');
                imagejpeg($this->newImage);
                break;

            case 'image/gif':
                header('Content-Type: image/gif');
                imagegif($this->newImage);
                break;

            case 'image/png':
                header('Content-Type: image/png');
                imagepng($this->newImage);
                break;
        }
    }


    public function resizeTo(int $width, int $height): void
    {
        if ($this->originalWidth > $width || $this->originalHeight > $height) {
            if ($this->originalWidth > $this->originalHeight) {
                $this->resizeHeight = $this->resizeHeightByWidth($width);
                $this->resizeWidth = $width;
            } else if ($this->originalWidth < $this->originalHeight) {
                $this->resizeWidth = $this->resizeWidthByHeight($height);
                $this->resizeHeight = $height;
            }
        } else {
            $this->resizeWidth = $width;
            $this->resizeHeight = $height;
        }

        $this->newImage = imagecreatetruecolor($this->resizeWidth, $this->resizeHeight);
        imagecopyresampled($this->newImage, $this->image, 0, 0, 0, 0, $this->resizeWidth, $this->resizeHeight, $this->originalWidth, $this->originalHeight);
    }


    private function resizeHeightByWidth(int $width): float
    {
        return floor(($this->originalHeight / $this->originalWidth) * $width);
    }


    private function resizeWidthByHeight(int $height): float
    {
        return floor(($this->originalWidth / $this->originalHeight) * $height);
    }
}

$img = new ResizeImage('cat.jpg');
$img->resizeTo(500, 500);
$img->render();