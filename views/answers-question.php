<section>
    <h4 class="my-4">View Answers</h4>
    <h4>Question</h4>
    <p id="question-for-answers" style="margin-bottom:20px"></p>

    <h4>Answers</h4>

    <table id="answers-question-table" class='table table-bordered table-hover table-striped'>
        <thead class="thead-dark">
            <tr>
                <th>AnswerID</th>
                <th>Answer</th>
                <th>User Name</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table rows will be generated dynamically -->
        </tbody>
    </table>

    <section id='add-answer-section' style='display:none'>
        <h4 class="my-4">Add a New Answer</h4>
        <form id='answer-form'>
            <div class="form-group">
                <label for="answer-form-answer">Answer</label>
                <textarea class="form-control" id="answer-form-answer" name='answer' rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="answer-form-reference">Reference</label>
                <input class="form-control" id="answer-form-reference" name='reference' required>
            </div>

            <button type="submit" class="btn btn-primary mt-4" id='answer-form-btn-submit'>Submit Answer</button>
        </form>
    </section>
</section>