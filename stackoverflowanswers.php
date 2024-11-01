<?php

/**
 * Plugin Name: StackOverflow Answers Widget
 * Plugin URI: http://my.geek.nz
 * Description: Display StackOverflow answers.
 * Version: 0.7
 * Author: Mohamed Alsharaf
 * Author URI: http://my.geek.nz
 */
class SOAnswersWidget extends WP_Widget
{
    /**
     * Plugin id
     *
     * @var string
     */
    private $idBase = 'osanswers-widget';

    /**
     * Plugin translation domain
     *
     * @var string
     */
    private $translateDomain = 'osanswerswidget';

    /**
     * Plugin base path
     * @var string
     */
    private $basePath;

    /**
     * Cache file life time in seconds
     *
     * @var int
     */
    private $cacheTime = '86400';

    /**
     * Plugin settings
     *
     * @var array
     */
    private $widgetSettings;

    /**
     * Array of sort options
     *
     * @var array
     */
    private $sortOptions;

    /**
     * Path to cache file
     *
     * @var string
     */
    private $cacheFile;

    /**
     * Constructor
     */
    public function SOAnswersWidget()
    {
        $this->basePath = ABSPATH . 'wp-content/plugins/stackoverflow-answers-widget/';
        $this->sortOptions = array(
            'score-asc' => $this->translate('Hightest score'),
            'oldest'    => $this->translate('Oldest'),
            'newest'    => $this->translate('Newest'),
        );

        // Widget settings
        $widgetOps = array(
            'classname'   => $this->translateDomain,
            'description' => $this->translate('Display StackOverflow answers')
        );

        // Widget control settings
        $controlOps = array('id_base' => $this->idBase, 'width' => 251);

        // Create the widget
        $this->WP_Widget($this->idBase, $this->translate('StackOverflow Answers Widget'), $widgetOps, $controlOps);
    }

    /**
     *
     * @param array $args
     * @param array $instance
     * @see WP_Widget::widget
     */
    function widget($args, $instance)
    {
        extract($args);
        $this->widgetSettings = $instance;
        $data = $this->getAnswers();
        $title = apply_filters('widget_title', $instance['title']);

        // Before widget (defined by themes)
        echo $before_widget;

        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        // Widget content
        echo $this->renderView('display', array(
            'maxAnswers' => (int) $instance['totalAnswers'],
            'answers'    => $data['items'],
            'total'      => $data['total']
        ));
        // After widget (defined by themes)
        echo $after_widget;
    }

    /**
     * Echo the settings update form
     *
     * @param array $instance Current settings
     */
    function form($instance)
    {
        $instance = wp_parse_args((array) $instance, array('sort' => '', 'totalAnswers' => '2', 'appKey' => '', 'userid' => '', 'title' => ''));

        echo $this->renderView('form', array(
            'title'  => esc_html($instance['title']),
            'userId' => esc_html($instance['userid']),
            'total'  => esc_html($instance['totalAnswers']),
            'sort'   => esc_html($instance['sort']),
        ));
    }

    /**
     * Translate a string
     *
     * @param string $string
     * @return string
     */
    protected function translate($string)
    {
        return __($string, $this->translateDomain);
    }

    /**
     * Render a view
     *
     * @param string $name
     * @param array $data
     * @return string
     */
    protected function renderView($name, $data = array())
    {
        // Start output buffer
        ob_start();

        $path = $this->basePath . 'views/' . $name . '.php';

        if (!file_exists($path)) {
            trigger_error(sprintf("View file '%s' does not exists.", $path), E_USER_NOTICE);
        } else {
            extract($data);
            include $path;
        }

        // Output content
        return ob_get_clean();
    }

    /**
     * Get the absolute cache file
     *
     * @return string
     */
    protected function getCacheFile()
    {
        // File name is unique per widget
        $filename = md5($this->id) . 'data.txt';
        $this->cacheFile = $this->basePath . $filename;

        return $this->cacheFile;
    }

    /**
     * Ger list of answers
     *
     * @param int $userId
     * @return array
     */
    protected function getAnswers()
    {
        // User id for stackoverflow user is required
        if (empty($this->widgetSettings['userid'])) {
            return array();
        }

        // If cache file exist
        if (file_exists($this->getCacheFile())) {
            $cacheContent = @file_get_contents($this->cacheFile);
            // Create new cache if cache content is empty
            if (empty($cacheContent)) {
                return $this->createCacheFile();
            }

            // Create new cache if file update timestamp is older than one day
            $timeDiff = time() - filemtime($this->cacheFile);
            if ($timeDiff > $this->cacheTime) {
                return $this->createCacheFile();
            }

            // Convert cache file content into an associative array
            return json_decode($cacheContent, true);
        }

        // Create new cache
        return $this->createCacheFile();
    }

    /**
     * Download stackoverflow answers
     *
     * @return array
     */
    protected function createCacheFile()
    {
        $dataUrl = 'http://api.stackexchange.com/2.2/users/' . $this->widgetSettings['userid'] . '/answers/'
                . '?filter=!t**.b(c1-MynR)6cXLyCBHjQmetoS_a&site=stackoverflow&'
                . 'pagesize=' . (int) $this->widgetSettings['totalAnswers'] . '&sort=';

        switch ($this->widgetSettings['sort']) {
            case 'score-asc':
                $dataUrl .= 'votes&order=desc';
                break;

            case 'oldest':
                $dataUrl .= 'creation&order=asc';
                break;

            case 'newest':
            default:
                $dataUrl .= 'creation&order=desc';
                break;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $dataUrl);
        curl_setopt($curl, CURLOPT_REFERER, $dataUrl);
        curl_setopt($curl, CURLOPT_PORT, 80);
        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSLVERSION, 3);
        curl_setopt($curl, CURLOPT_POST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5184000);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/json"));
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');

        $response = curl_exec($curl);
        $data = array();

        if (!curl_errno($curl)) {
            // Save content into cache file
            if (file_put_contents($this->cacheFile, $response) !== false) {
                // Convert response into an associative array
                $data = json_decode($response, true);
            }
        }

        curl_close($curl);

        return $data;
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['totalAnswers'] = strip_tags($new_instance['totalAnswers']);
        $instance['userid'] = strip_tags($new_instance['userid']);
        $instance['title'] = esc_html($new_instance['title']);
        $instance['sort'] = esc_html($new_instance['sort']);

        // Delete the cache file on updating an instance of a widget
        @unlink($this->getCacheFile());

        return $instance;
    }

    function update_callback($widget_args = 1)
    {
        if (isset($_POST['delete_widget']) && $_POST['delete_widget']) {
            // Delete the cache file on deleting an instance of a widget
            if (isset($_POST['the-widget-id'])) {
                @unlink($this->getCacheFile());
            }
        }

        return parent::update_callback($widget_args);
    }

}

/**
 * Add function to widgets_init that'll load our widget.
 * Add widget syle sheet to the page head
 *
 * @since 0.1
 */
add_action('widgets_init', 'initSOAnswersWidget');
add_action('wp_head', 'headSOAnswersWidget');

/**
 * Register our widget.
 * 'SOAnswersWidget' is the widget class used below.
 *
 * @since 0.1
 */
function initSOAnswersWidget()
{
    register_widget('SOAnswersWidget');
}

/**
 * Insert css style file into <head>
 *
 * @since 0.1
 */
function headSOAnswersWidget()
{
    echo '<link type="text/css" rel="stylesheet" href="' . get_settings('siteurl') . '/wp-content/plugins/stackoverflow-answers-widget/styles.css" />' . "\n";
}
