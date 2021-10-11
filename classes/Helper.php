<?php
namespace mvr;

defined( 'ABSPATH' ) or exit;

class Helper {
    function __construct() {

    }

    public static function get_file_list( $dir ) {
        return scandir( $dir );
    }

    public static function get_file_name_list( $dir ) {
        $file_list = self::get_file_list( $dir );
        $result    = [];

        foreach ( $file_list as $file ) {
            $result[] = explode( '.', $file )[0];
        }

        return $result;
    }
}