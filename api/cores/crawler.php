<?php
abstract class Crawler {
    abstract public function getData();
    abstract public function transformData( $data );
}