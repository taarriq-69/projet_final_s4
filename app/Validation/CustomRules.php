<?php
 namespace App\Validation;

 class CustomRules{
    public function formeValide(string $value): bool{
        return str_starts_with($value, '03');
    }
 }
?>