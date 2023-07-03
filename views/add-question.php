<section>
    <h4 class="my-4" id='question-form-header'>Tạo câu hỏi mới</h4>
    <form id='question-form'>
        <div class="form-group">
            <label for="question-form-question">Câu hỏi</label>
            <textarea class="form-control" id="question-form-question" name='question' rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="question-form-tags">Tag</label>
            <input class="form-control" id="question-form-tags" name='tags' placeholder="Name, Age, ..." required>
        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block mt-4" id='question-form-btn-submit'>Tạo</button>
    </form>
</section>