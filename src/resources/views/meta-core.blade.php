<title>{{ meta_title() }}</title>
<meta charset="utf-8">
@if(!empty(meta_setting('facebook_app_id')))
<meta property="fb:app_id" content="{{meta_setting('facebook_app_id')}}">
@endif
@if(!empty(meta_setting('google_signin_client_id')))
<meta name="google-signin-client_id" content="{{meta_setting('google_signin_client_id', '')}}">
@endif
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
@php
$pageMetas = PageMeta::getPageMetas();
@endphp
<meta name="image" content="{{PageMeta::getPageImage()}}">
<meta property="og:title" content="{{PageMeta::getPageTitle()}}">
<meta property="og:image:alt" content="{{PageMeta::getPageTitle()}}">
<meta property="og:url" content="{{PageMeta::currentUrl()}}">
{{--  twitter meta tags  --}}
<meta name="twitter:title" content="{{PageMeta::getPageTitle()}}">
<meta name="twitter:url" content="{{PageMeta::currentUrl()}}" />
{{--  end twitter meta tags  --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
@if(auth()->check() && auth()->user()->mobichat_token)
<meta name="mobichat-token" content="{{ auth()->user()->mobichat_token }}">
@endif
@foreach($pageMetas as $metaName => $metaContent)
@if($metaName != 'title' && $metaName != 'image')
<meta {{PageMeta::getMetaAttributeName($metaName)}}="{{$metaName}}" content="{{PageMeta::getPageMeta($metaName)}}">
@endif 
@endforeach

<link rel="canonical" href="{{PageMeta::currentUrl()}}" />
