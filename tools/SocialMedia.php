<?php
namespace qeywork;

/**
 * @author lil-Dexx
 */
 
class SocialMedia
{
    public $providers = array (
        'Mail' => array (
            'text' => 'Send via e-mail',
            'link' => 'mailto:type%20email%20address%20here?subject=I%20wanted%20to%20share%20this%20post%20with%20you%20from%20{domain}&body={title}%20-%20{url}'
        ),
        'Facebook' => array (
            'text' => 'Share on Facebook',
            'link' => 'http://www.facebook.com/sharer.php?u={url}&t={title}'
        ),
        'Digg' => array (
            'text' => 'Digg it',
            'link' => 'http://digg.com/submit?url={url}&title={title}'
        ),
        'Reddit' => array (
            'text' => 'Share this on Reddit',
            'link' => 'http://reddit.com/submit?url={url}&title={title}'
        ),
        'Twitter' => array (
            'text' => 'Tweet this',
            'link' => 'http://twitter.com/home?status={url} - {title}'
        ),
        'Google Buzz' => array (
            'text' => 'Share this on Google Buzz!',
            'link' => 'http://www.google.com/buzz/post?message={title}&url={url}'
        ),
        'Delicious' => array (
            'text' => 'Share this on del.icio.us',
            'link' => 'http://del.icio.us/post?url={url}&title={title}'
        ),
        'StumbleUpon' => array (
            'text' => 'Sharing this on StumbleUpon',
            'link' => 'http://www.stumbleupon.com/submit?url={url}&title={title}'
        ),
        'Float' => array (
            'text' => 'Float it',
            'link' => 'http://www.designfloat.com/submit.php?url={url}&title={title}'
        ),
        'Bump' => array (
            'text' => 'Bump It',
            'link' => 'http://designbump.com/node/add/drigg/?url={url}&title={title}'
        )
    );
    
    public $imagePath;
    public $url;
    public $title;
    public $description;
    
    /**
     * Constructor
     */
    public function SocialMedia($url, $title)
    {
        global $config;
        $this->url = $url;
        $this->title = $title;
        $this->imagePath = $config['places']['images']->addDir('social-media');
    }
    
    public function renderShare($providerList)
    {
        $providers = $this->providers;
        foreach ($providerList as $provider)
            $providers[$provider]['enabled'] = true;
        
        $template = qeyNode('a')->cls('social-media-share')
            ->attr('href', '{url}')->attr('title', '{text}');
        
        $html = '';
        foreach ($providerList as $providerName)
        {
            $provider = $this->providers[$providerName];
            
            $aUrl = processTemplates(array(
                'url' => urlencode($this->url),
                'title' => urlencode($this->title)
            ), $provider['link']);
            $html .= processTemplates(array(
                'url' => $aUrl,
                'text' => $provider['text']
            ), $template);
        }
        
        return $html;
    }
}
?>