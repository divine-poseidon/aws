<?php


namespace app\storage;

interface StorageInterface
{
    public function add(array $data): void;
    public function get(array $params);
}