@verbatim<?xml version="1.0"?> @endverbatim
<feed version="2.0">
    <title>SketchNI</title>
    <id>https://sketchni.uk</id>
    <copyright>Copyright Sketch {{ date("Y") }}</copyright>
    <description>An RSS feed of my blog posts.</description>
    <language>en-us</language>
    <updated>{{ $last_updated }}</updated>
    <link href="https://sketchni.uk" rel="self" />
    <image>
        <url>/images/chibinobg.png</url>
        <title>SketchNI Avatar</title>
        <link>https://sketchni.uk</link>
    </image>
    <author>
        <name>Denver Freeburn</name>
        <email>sketch@sketchni.uk</email>
        <uri>https://sketchni.uk</uri>
    </author>
    @foreach($posts as $post)
    <entry>
        <title>{{ $post->title }}</title>
        <id>{{ route('blog.show', ['post' => $post->id]) }}</id>
        <updated>{{ $post->updated_at }}</updated>
        <link href="{{ route('blog.show', ['post' => $post->id]) }}" rel="alternative" />
        <content xml:base="{{ config('app.url') }}" xml:lang="en" type="html">
            {!! $post->content !!}
        </content>
    </entry>
    @endforeach
</feed>
