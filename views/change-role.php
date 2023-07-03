<section>
    <h4 class="my-4" id='question-form-header'>Update Role</h4>
    <form id='role-form'>
        <div class="form-group">
            <label for="role-form-select">New Role</label>
            <select class="form-control" id="role-form-select" name='role' required>
                <option value="">-- Choose role --</option>
                <option value="Questioner">Questioner</option>
                <option value="Answerer">Answerer</option>
                <option value="Evaluater">Evaluater</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block mt-4" id='question-form-btn-submit'>Update</button>
    </form>
</section>