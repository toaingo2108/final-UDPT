<section>
    <div id='questions-header' class="d-flex align-items-center justify-content-between">
        <h4 class="my-4">Questions</h4>
        <form class="form-inline my-2 my-lg-0" id='search-form'>
            <input id="search-text" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" />
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
                Search
            </button>
        </form>
    </div>
    <table id="questions-table" class='table table-bordered table-hover table-striped'>
        <thead class="thead-dark">
            <tr>
                <th>QuestionID</th>
                <th>Question</th>
                <th>UserID</th>
                <th>Tags</th>
                <th>Created Date</th>
                <th>Number of Answerers</th>
                <th>View Answers</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table rows will be generated dynamically -->
        </tbody>
    </table>
</section>