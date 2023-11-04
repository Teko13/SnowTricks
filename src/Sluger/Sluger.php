<?php
namespace App\Sluger;

class Sluger {
    public function slugify(string $string): string {
        return strtolower(str_replace(" ", "-", $string));
    }
}