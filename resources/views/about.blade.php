<x-blog-layout meta-title="About NUTC樂青志工" meta-description="關於樂青志工">
        <!-- Post Section -->
        <section class="w-full flex flex-col items-center px-3">

    <article class="w-full flex flex-col shadow my-4">
        <!-- Article Image -->
        <a href="#" class="hover:opacity-75">
            <img src="/storage/{{$widget->image}}" class="w-full">
        </a>
        <div class="bg-white flex flex-col justify-start p-6">
            <h1 class="text-3xl font-bold hover:text-gray-700 pb-4">
                {{$widget->title}}
            </h1>
            <div>
                {!! $widget->content !!}
            </div>
        </div>
    </article>

    </section>
</x-blog-layout>