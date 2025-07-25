<div class="modal-header">
  <h6 class="modal-title fs-5" id="">{{ __('Copier Quiz') }}</h6>
</div>
<div>
    <form action="{{ route('admin.course-chapter.quiz-duplicate.store') }}" method="POST" class="add_lesson_form instructor__profile-form">
        @csrf
        @method('POST')
        <div>
            <p>Copie de quiz du cours : <strong>{{$selectedCourse->title}}</strong> de chapitre: <strong>{{$chapter->title}}</strong> à :</p>
        </div>
        <input type="hidden" name="chapter_item_id" value="{{ $chapter_item_id }}">
        <div>
            <div class="form-grp">
                <label for="course_id">{{ __('Course') }} <code>*</code></label>
                <select name="chapter_id" id="course_id" class="form-control">
                    <option value="">{{ __('Select') }}</option>
                    @foreach ($courses as $course)
                        <optgroup label="{{ $course->title }}">
                            @foreach ($course->chapters as $chapter)
                                @if (!is_null($chapter->session_id))
                                    @php
                                        $session = \App\Models\CoursSession::findOrFail($chapter->session_id);
                                    @endphp
                                    <option value="{{ $chapter->id }}">{{ $chapter->title }} ({{ $session->title }})</option>
                                @else
                                    <option value="{{ $chapter->id }}">{{ $chapter->title }}</option>
                                @endif
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary submit-btn" >{{ __('Copier') }}</button>
        </div>
    </form>
</div>
