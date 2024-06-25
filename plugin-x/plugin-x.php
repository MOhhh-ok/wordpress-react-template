<?php

/*
Plugin Name: Plugin-X
Description: 
Author: 
Version: 0.0.4
License: 
Requires PHP: 
*/

add_action('admin_menu', 'plugin_x_add_admin_menu');

function plugin_x_add_admin_menu()
{
    add_menu_page(
        'Plugin-X Settings', // ページタイトル
        'Plugin-X',          // メニュータイトル
        'manage_options',    // 権限
        'plugin-x',          // スラッグ
        'plugin_x_settings_page' // コールバック関数
    );
}

function plugin_x_settings_page()
{
    $script = plugin_dir_url(__FILE__) . 'react/dist/entry1.js';
    $data = json_encode(["a" => 1, "b" => 2]);
?>
    <div>
        <h1>Plugin-X react test</h1>
        <div class="plugin-x-test" data-props='<?php echo $data; ?>'></div>
        <script type="module" src="<?php echo $script ?>"></script>
    </div>
<?php
}
