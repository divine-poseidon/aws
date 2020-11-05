<?php


namespace app\storage;


use Aws\Result;

interface StorageInterface
{
    public function add(array $data): void;
    public function get(array $params);
}