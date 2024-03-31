@extends('layouts.base')

@section('title', 'Ideaflow')

@section('content')
<div class="mt-3">
    <div class="container">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('outlines.store')}}" method="POST">
                            @csrf
                            <div>
                                <div class="mb-3">
                                    <div class="h3">What is the topic of your blog post?</div>
                                    <input name="title" class="form-control" value="{{$outline ? $outline->title : ''}}" placeholder="eg. How to make money online" />
                                    @error('title')
                                        <div class="my-1 alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="h3">What are the important points to include in your content?</div>
                                    <textarea rows="7" name="topic" class="form-control" placeholder="e.g, How to choose the right product for your business">{{$outline ? $outline->topic : ''}}</textarea>
                                    @error('topic')
                                        <div class="my-1 alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex">
                                    <div>
                                        <select name="tone" class="form-select">
                                            <option value="">Select tones</option>
                                            @foreach($tones as $tone => $label)
                                            @if($tone == $selected_tone)
                                            <option selected value="{{$tone}}">{{$label}} {{$tone}}</option>
                                            @else
                                            <option value="{{$tone}}">{{$label}} {{$tone}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @error('tone')
                                            <div class="my-1 alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="ms-auto">
                                        <button id="btn-send" class="btn btn-primary">
                                            <span>Submit</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <textarea id="editor"></textarea>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
<script>
    const easyMDE = new EasyMDE({
        element: document.getElementById('editor'),
        spellChecker: false,
        hideIcons: ["fullscreen", "undo", "redo", "side-by-side", ],
    });

    const btnSend = document.getElementById("btn-send");
    const inputMesage = document.getElementById("input-mesage");
    let stillWriting = false;

    const btnLoadingEl = `<div class="spinner-border spinner-border-sm text-white" role="status"></div><span class="ms-2">Generating</span>`;
    const btnEl = `<span>Submit</span>`;

    const triggerStreaming = (outlineId) => {
        stillWriting = true;
        btnSend.classList.add("disabled")
        btnSend.innerHTML = btnLoadingEl;
        const source = new EventSource(
            `/outlines/generate/${outlineId}`,
        );
        let sseText = "";

        source.addEventListener("update", (event) => {
            if (event.data === "<END_STREAMING_SSE>") {
                source.close();
                stillWriting = false;
                btnSend.innerHTML = btnEl;
                btnSend.classList.remove("disabled")
                return;
            }
            const data = JSON.parse(event.data);
            if (data.text) {
                sseText += data.text;
                easyMDE.value(sseText);
            }
        });
    };

    btnSend.addEventListener("click", () => {
        submitSendMessage()
    })

    const outlineId = @json($outline_id);
    if (outlineId) {
        console.log(outlineId)

        setTimeout(() => {
            triggerStreaming(outlineId);
        }, 300);
    }
</script>
@endpush