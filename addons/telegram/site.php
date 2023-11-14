<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2023 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

//no direct accees

defined('_JEXEC') or die('Restricted Aceess');
class SppagebuilderAddonTelegram extends SppagebuilderAddons {
    public function getFile($api_token, $file_id) {
        $get_file_response = file_get_contents("https://api.telegram.org/bot$api_token/getFile?file_id=$file_id&disable_web_page_preview=1");
        $file_response_json = json_decode($get_file_response);
        $this->generateImageUrl($api_token);
        $file_path_url = 'plugins/sppagebuilder/webpalace/addons/telegram/image.php?path=' . $file_response_json->result->file_path;

        return $file_path_url;
    }

    public function generateImageUrl($token = null) {
        $path = '$path';
        $filename = __DIR__ . '/image.php';
        
        $php_code = '<?php $path = isset($_GET["path"]) ? $_GET["path"] : false;';
        $php_code .= 'if ($path) {';
        $php_code .= <<<EOD
        echo file_get_contents("https://api.telegram.org/file/bot$token/".$path);
        EOD;
        $php_code .= '} else {';
        $php_code .= 'header("Location: /");';
        $php_code .= '}?>';
        
        if (!file_exists($filename)) {
            file_put_contents($filename, $php_code);
        }
    }

    public function render() {
        
        global $channel_username;
        $settings = $this->addon->settings;

        $api_token          = (isset($settings->api_token) && $settings->api_token) ? $settings->api_token : '';
        $title              = (isset($settings->title) && $settings->title) ? $settings->title : '';
        $heading_selector   = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';
        $channel_username   = (isset($settings->channel_username) && $settings->channel_username) ? $settings->channel_username : '';
        $type               = (isset($settings->type) && $settings->type) ? $settings->type : '';
        $posts_count        = (isset($settings->posts_count) && $settings->posts_count) ? $settings->posts_count : '';
        $bottom_desc        = (isset($settings->bottom_desc) && $settings->bottom_desc) ? $settings->bottom_desc : '';
        $class              = (isset($settings->class) && $settings->class) ? ' ' . $settings->class : '';
        
        $response = file_get_contents("https://api.telegram.org/bot$api_token/getUpdates");
        $response_json = json_decode($response);
        
        $posts = $response_json->ok ? array_filter($response_json->result, function($post) {
            if(isset($post->channel_post->chat->username)) {
                return ($post->channel_post->chat->username == $GLOBALS['channel_username']);
            }
        }) : [];
        
        arsort($posts);

        $i = 1;
        $col = '4';

        ($posts_count === '1') ? $col = '12' : $col;
        ($posts_count === '2') ? $col = '6' : $col;
        ($posts_count === '3') ? $col = '4' : $col;
        ($posts_count === '4') ? $col = '3' : $col;
        ($posts_count > '4') ? $col = '4' : $col;

        $output = '';
        $output .= '<div class="sppb-addon sppb-addon-telegram' . $class . '">';
        $output .= ($title) ? '<'.$heading_selector.' class="sppb-addon-title">' . $title . '</'. $heading_selector.'>' : '';
        $output .= '<div class="sppb-row sppb-addon-telegram-row">';
        if (count($posts) == 0) {
            $output .= 'No actual posts yet.';
        } else {
            foreach($posts as $post) {
                
                if ( $i > $posts_count ) {
                    break;
                }

                if($type == 'is_channel' 
                    && isset($post->channel_post) 
                    && isset($post->channel_post->chat->username) 
                    && $post->channel_post->chat->username === $channel_username
                ) {
                    $post_caption = (isset($post->channel_post->caption)) ? substr($post->channel_post->caption, 0, 273) : '';
                    $post_text = (isset($post->channel_post->text)) ? substr($post->channel_post->text, 0, 1400) : '';
                    $post_title = substr($post_caption, 0, 100);
                    $post_link = 'https://t.me/' . $channel_username . '/' . $post->channel_post->message_id;
                    $post_file_id = '';
                    //Simple message
                    if (isset($post->channel_post->text) && $post->channel_post->text) {
                        $output .= '<div class="sppb-col-md-'. $col .'">';
                            $output .= '<div class="post-item">';
                                $output .= $post_text;
                                $output .= '<br>';
                                $output .= '<a class="post-item__" href="' . $post_link . '" target="_blank">';
                                    $output .= '👉 ' . $bottom_desc;
                                    $output .= ' @' . $post->channel_post->chat->username;
                                $output .= '</a>';
                            $output .= '</div>';
                        $output .= '</div>';
                    }
                    //End Simple message
                    //Media Post
                    if(isset($post->channel_post->video) && $post->channel_post->video) {
                        $post_file_id = $post->channel_post->video->thumb->file_id;
                    }
                    if (isset($post->channel_post->photo) && $post->channel_post->photo) {
                        $post_file_id = $post->channel_post->photo[2]->file_id;
                    }

                    $output .= '<div class="sppb-col-md-' . $col . '">';
                        $output .= '<div class="post-item">';
                            $output .= '<img class="post-item__thumb" src="' . $this->getFile($api_token, $post_file_id) . '" />';
                            $output .= '<a href="' . $post_link . '" target="_blank">';
                                $output .= '<h4 class="post-item__title">' . $post_title . '...</h4>';
                            $output .= '</a>';
                            $output .= '<p class="post-item__desc">' . $post_caption . '...</p>';
                            $output .= '<a class="post-item__" href="' . $post_link . '" target="_blank">';
                                $output .= '👉 ' . $bottom_desc;
                                $output .= ' @' . $post->channel_post->chat->username;
                            $output .= '</a>';
                        $output .= '</div>';
                        $output .= '</div>';
                    //End Media Post
                }

                $i++;
            }
        }

        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }

    public function css() {
        $addon_id = '#sppb-addon-' . $this->addon->id;
        $css = '';
        $css .= $addon_id . ' .post-item {padding: 7px;border: 1px solid #eee;margin-bottom:30px;}';
        $css .= $addon_id . ' .post-item__thumb {width:100%;height:280px;object-fit:cover;margin-bottom:8px;}';

        return $css;
    }

    public static function getTemplate() {
        $output = '';

        return $output;
    }
}

//Features
// Выбор вариантов иконок для $bottom_desc
// Обединить шаблоны текстовых сообщений с постами из видео и фото
// Выставлять качество изображений постов