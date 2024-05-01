<?php

namespace App\Traits;

trait HttpRequest{
  
  public function formatTags(array $data){
    $data['tags'] = implode(',', $data['tags']);
    return $data;
  }
}