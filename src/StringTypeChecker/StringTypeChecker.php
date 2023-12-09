<?php
namespace App\StringTypeChecker;

class StringTypeChecker {
    
    public function isEmbed(string $string): bool
    {
        $pattern = '/<iframe .*<\/iframe>|<embed .*<\/embed>|<object .*<\/object>|<video .*<\/video>|<audio .*<\/audio>/i';
    return preg_match($pattern, $string) === 1;
    }
    public function isUrl(string $string): bool
    {
        return filter_var($string, FILTER_VALIDATE_URL) !== false;
    }
}
