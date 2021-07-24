<?php

namespace bachphuc\LaravelMeta;

class PageMeta
{
    protected $pageInformation = [];
    protected $defaultMetaTags = [
        'image',
        'description',
        'keywords',
        'author'
    ];

    protected $settingMetas = [];
    protected $subject = null;
    protected $metaNameAttributes = ['theme-color'];

    public function getPageMeta($key)
    {
        if(isset($this->pageInformation[$key])){
            $value = $this->pageInformation[$key];
            if($key == 'image' || $key == 'og:image' || $key == 'twitter:image'){
                return asset($value);
            }
            else if($key == 'description' || $key == 'og:description'){
                return str_limit($value, 160);
            }
            if(is_array($value)){       
                try{
                    return implode(', ', $value);
                }
                catch(\Exception $e){
                    return '';
                }
            }
            return $value;
        }
        return null;
    }

    public function getPageTitle()
    {
        $title = self::getPageMeta('title');
        $setingTitle = isset($this->settingMetas['title']) ? $this->settingMetas['title'] : '';
        $titles = [];
        if(!empty($title)) {
            $titles[] = str_limit($title, 120);
        }
        else if(!empty($this->subject)){
            $titles[] = $this->subject->getTitle();
        }

        if(!empty($setingTitle)){
            if(empty($titles)){
                $titles[] = $setingTitle;
            }
            else{
                $shortName = setting('site_short_name');
                if(!empty($shortName)){
                    $titles[] = $shortName;
                }
            }
        }
        
        if(!empty($titles)){
            return implode(' | ', $titles);
        }
        return config('app.name');
    }

    public static function addPageKeywords($keywords){
        if(empty($keywords)) return;
        if(!isset($this->pageInformation['keywords'])){
            $this->pageInformation['keywords'] = [$keywords];
        }
        else{
            if(is_array($keywords)){
                foreach($keywords as $k){
                    if(!in_array($k, $this->pageInformation['keywords'])){
                        $this->pageInformation['keywords'][] = $k;
                    }
                }
            }
            else{
                if(!in_array($keywords, $this->pageInformation['keywords'])){
                    $this->pageInformation['keywords'][] = $keywords;
                }
            }
        }
        
        $this->initDefaultTags();
    }

    public function setPageMeta($key, $value)
    {
        return $this->addPageMeta($key, $value);
    }

    public function getPageMetas()
    {
        // update meta tags
        foreach($this->settingMetas as $k => $v){
            if(!isset($this->pageInformation)){
                $this->pageInformation[$k] = $v;
            }
        }

        if(!empty($this->subject)){
            $subject = $this->subject;
            if(!isset($this->pageInformation['description'])){
                if(!empty($this->subject->getMetaDesc())){
                    $this->addPageMeta('description', $this->subject->getMetaDesc());
                }
            }
            if(!isset($this->pageInformation['title'])){
                if(!empty($this->subject->getMetaTitle())){
                    $this->addPageMeta('title', $this->subject->getMetaTitle());
                }
            }
            if(!isset($this->pageInformation['image'])){
                if(!empty($this->subject->getCover())){
                    $this->addPageMeta('image', $this->subject->getCover());
                }
            }

            $tags = $subject->getTags();
            if(!empty($tags)){
                $keywords = [];
                foreach($tags as $tag){
                    if(!in_array($tag->title, $keywords)){
                        $keywords[] = $tag->title;
                    }
                    if(!in_array($tag->search, $keywords)){
                        $keywords[] = $tag->search;
                    }
                }
                self::addPageKeywords($keywords);
            }
        }
        foreach($this->settingMetas as $k => $v)
        {
            if((!isset($this->pageInformation[$k]) || empty($this->pageInformation[$k])) && !empty($v)){
                $this->addPageMeta($k, $v);
            }
        }

        return $this->pageInformation;
    }

    public function initDefaultTags(){
        
    }

    public function addPageMeta($key, $value){
        if(empty($value)) return;
        if($key == 'keywords'){
            if(!isset($this->pageInformation['keywords'])){
                $this->pageInformation['keywords'] = [$value];
            }
            else{
                $this->pageInformation['keywords'][] = $value;
            }
            return;
        }
        $this->pageInformation[$key] = $value;
        $this->initDefaultTags();
        if(in_array($key, $this->defaultMetaTags)){
            if($key != 'keywords' && $key != 'author'){
                // facebook meta tags
                $this->pageInformation['og:'.$key] = $value;
                // twitter meta tags
                $this->pageInformation['twitter:'.$key] = $value;
            } 
        }
    }

    public function getMetaSetting($key, $default = null){
        if(!function_exists('setting')) return $default;
        $value = setting($key, $default);
        
        return $value;
    }

    public function currentUrl(){
        return request()->url();
    }

    public function getMetaAttributeName($key){
        if(strpos($key,"twitter") !== false){
            return 'name';
        }
        if(in_array($key, $this->defaultMetaTags)){
            return 'name';
        }
        if(in_array($key, $this->metaNameAttributes)){
            return 'name';
        }
        return 'property';
    }

    public function metaViewPath(){
        return 'meta::meta-core';
    }

    public function getPageImage(){
        $image = $this->getPageMeta('image');
        if(!empty($image)) return $image;
        return asset('images/cover.jpg');
    }

    public function setSubject($item){
        $this->subject = $item;
    }

    public function getSubject(){
        return $this->subject;
    }
}