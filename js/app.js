$(document).ready(() => {
  const urlParams = getParams(window.location.href);
  const page = urlParams.page;

  const user = getUser();
  const userInfo = $("#user-info");
  let html = "";
  userInfo.empty();
  if (user) {
    html = `
    <span class="mr-2">${user.role}</span>
    <span class="mr-2">${user.username}</span>
    <button class="btn btn-light" onclick="handleLogout()">Logout</button>
    `;
  } else {
    html = `
    <button id='btn-login' type="button" data-toggle="modal" data-target="#loginModal" class="btn btn-light">Login</button>
    `;
  }
  userInfo.append(html);

  handleAddMenu();

  const loginForm = $("#login-form");
  loginForm.submit(handleLoginFormSubmit);

  switch (page) {
    case "add-question":
      const questionForm = $("#question-form");
      questionForm.submit(handleQuestionFormSubmit);
      break;
    case "list-questions":
      getQuestions();
      $("#search-form").submit(handleSearch);
      break;
    case "answers-question":
      getAnswersQuestion();
      if (user?.role == "Admin" || user?.role == "Answerer") {
        $("#add-answer-section").css("display", "block");
        const answerForm = $("#answer-form");
        answerForm.submit(handleAnswerFormSubmit);
      }
      if (user?.role == "Admin" || user?.role == "Evaluater") {
        $("#answers-question-table thead tr").append("<th>Evaluate</th>");
      }
      break;
    case "last-answers":
      getLastAnswers();
      break;
    case "last-evaluates":
      getLastEvaluates();
      break;
    case "change-role":
      const roleForm = $("#role-form");
      roleForm.submit(handleRoleFormSubmit);
      break;
    default:
      break;
  }
});

const handleQuestionFormSubmit = (e) => {
  e.preventDefault();

  const user = getUser();

  if (!user) {
    return $("#btn-login").click();
  }

  if (!(user.role == "Admin" || user.role == "Questioner")) {
    return alert("Bạn không có quyền tạo câu hỏi với vai trò " + user.role);
  }

  const form = new FormData(e.target);
  const formData = {
    question: form.get("question"),
    userID: 1,
    tags: form.get("tags"),
    createdDate: getCurrentDate(),
  };

  $.ajax({
    url: "../controllers/QuestionController.php",
    type: "POST",
    data: { action: "addQuestion", ...formData },
    success: (data) => {
      alert(data);
      window.location.reload();
    },
    error: (xhr, status, error) => {
      // Handle error response
      if (xhr.status === 400) {
        alert(xhr.responseText);
      } else {
        alert("An error occurred: " + error);
      }
    },
  });
};

const handleLoginFormSubmit = (e) => {
  e.preventDefault();
  const form = new FormData(e.target);
  const data = {
    username: form.get("username"),
    password: form.get("password"),
  };
  $.ajax({
    url: "../controllers/AuthController.php",
    type: "POST",
    data: { action: "login", ...data },
    success: (data) => {
      form.set("username", "");
      form.set("password", "");
      alert(data);
      localStorage.setItem("user", data);
      window.location.reload();
    },
    error: (xhr, status, error) => {
      // Handle error response
      if (xhr.status === 400) {
        alert(xhr.responseText);
      } else {
        alert("An error occurred: " + error);
      }
    },
  });
};

const handleAnswerFormSubmit = (e) => {
  e.preventDefault();
  const urlParams = getParams(window.location.href);
  const id = urlParams.id;

  if (!id) return;

  const form = new FormData(e.target);
  const data = {
    questionID: +id,
    userID: getUser().userID.toString(),
    answer: form.get("answer"),
    reference: form.get("reference"),
    createdDate: getCurrentDate(),
  };

  $.ajax({
    url: "../controllers/AnswerController.php",
    type: "POST",
    data: { action: "addAnswer", ...data },
    success: (data) => {
      form.set("answer", "");
      form.set("reference", "");
      alert(data);
      window.location.reload();
    },
    error: (xhr, status, error) => {
      // Handle error response
      if (xhr.status === 400) {
        alert(xhr.responseText);
      } else {
        alert("An error occurred: " + error);
      }
    },
  });
};

const handleRoleFormSubmit = (e) => {
  e.preventDefault();
  const form = new FormData(e.target);
  const data = {
    userID: getUser().userID.toString(),
    role: form.get("role"),
  };

  $.ajax({
    url: "../controllers/UserController.php",
    type: "POST",
    data: { action: "updateRole", ...data },
    success: (data) => {
      form.set("role", "");
      alert(data);
      handleLogout();
    },
    error: (xhr, status, error) => {
      // Handle error response
      if (xhr.status === 400) {
        alert(xhr.responseText);
      } else {
        alert("An error occurred: " + error);
      }
    },
  });
};

const getUser = () => {
  const user = localStorage.getItem("user")
    ? JSON.parse(localStorage.getItem("user"))
    : null;

  return user;
};

const handleLogout = () => {
  localStorage.removeItem("user");
  window.location.reload();
};

const getQuestions = () => {
  let searchText = $("#search-text").val();
  if (searchText == null || searchText == "") {
    searchText = "%";
  }
  $.ajax({
    url: "../controllers/QuestionController.php",
    type: "GET",
    data: { action: "getAllQuestions", searchText },
    success: (data) => {
      const questions = JSON.parse(data);
      $("#questions-table tbody").empty();
      $.each(questions, (index, question) => {
        const row = `
        <tr>
          <td>${question.QuestionID}</td>
          <td>${question.Question}</td>
          <td>${question.UserID}</td>
          <td>${question.Tags}</td>
          <td>${question.CreatedDate}</td>
          <td>${question.NumberAnswerers || 0}</td>
          <td>
            <button 
              type="button" 
              class="btn btn-info btn-sm ml-2" 
              onClick="handleRedirect(event)" 
              data-question-id="${question.QuestionID}" 
              data-question="${question.Question}" 
              data-page="answers-question"
            >
              Answer
            </button>
          </td>
        </tr>
        `;
        $("#questions-table tbody").append(row);
      });
    },
  });
};

const handleSearch = (e) => {
  e.preventDefault();
  getQuestions();
};

const getAnswersQuestion = () => {
  const urlParams = getParams(window.location.href);
  const id = urlParams.id;
  const questionParam = urlParams.question;

  const user = getUser();

  if (!id) return;

  $.ajax({
    url: "../controllers/AnswerController.php",
    type: "GET",
    data: { action: "getAllAnswersByQuestion", questionID: id },
    success: (data) => {
      const tableBody = $("#answers-question-table tbody");
      const question = $("#question-for-answers");
      const answers = JSON.parse(data);
      question.empty();
      question.append(questionParam);

      tableBody.empty();
      $.each(answers, (index, answer) => {
        const row = `
        <tr>
          <td>${answer.AnswerID}</td>
          <td>${answer.Answer}</td>
          <td>${answer.UserID}</td>
          <td>${answer.CreatedDate}</td>
          ${
            (user?.role == "Admin" || user?.role == "Evaluater") &&
            `<td>
            <select name='rate' required onchange="handleEvaluateAnswer(event)" data-answer-id="${answer.AnswerID}">
            <option value="">----------</option>
            <option value="1STAR">1STAR</option>
            <option value="2STAR">2STAR</option>
            <option value="3STAR">3STAR</option>
            <option value="4STAR">4STAR</option>
            <option value="5STAR">5STAR</option>
        </select>
            </td>`
          }
        </tr>
        `;
        tableBody.append(row);
      });
    },
  });
};

const getLastAnswers = () => {
  $.ajax({
    url: "../controllers/AnswerController.php",
    type: "GET",
    data: { action: "getLastAnswers" },
    success: (data) => {
      const tableBody = $("#last-answers-table tbody");
      const answers = JSON.parse(data);

      tableBody.empty();
      $.each(answers, (index, answer) => {
        const row = `
        <tr>
          <td>${answer.AnswerID}</td>
          <td>${answer.Question}</td>
          <td>${answer.Answer}</td>
          <td>${answer.UserID}</td>
          <td>${answer.CreatedDate}</td>
          <td>
            <button 
              type="button" 
              class="btn btn-info btn-sm ml-2" 
              onClick="handleRedirect(event)" 
              data-question-id="${answer.QuestionID}" 
              data-question="${answer.Question}" 
              data-page="answers-question"
            >
              Answer
            </button>
          </td>
        </tr>
        `;
        tableBody.append(row);
      });
    },
  });
};

const getLastEvaluates = () => {
  $.ajax({
    url: "../controllers/AnswerEvaluateController.php",
    type: "GET",
    data: { action: "getLastAnswerEvaluates" },
    success: (data) => {
      const tableBody = $("#last-evaluates-table tbody");
      const evaluates = JSON.parse(data);

      tableBody.empty();
      $.each(evaluates, (index, evaluate) => {
        const row = `
        <tr>
          <td>${evaluate.EvaluateID}</td>
          <td>${evaluate.AnswerID}</td>
          <td>${evaluate.Answer}</td>
          <td>${evaluate.UserID}</td>
          <td>${evaluate.RateCategory}</td>
          <td>${evaluate.CreatedDate}</td>
          
        </tr>
        `;
        tableBody.append(row);
      });
    },
  });
};

const handleEvaluateAnswer = (e) => {
  const answerID = $(e.target).data("answer-id");
  const user = getUser();
  if (!user) {
    return $("#btn-login").click();
  }

  const data = {
    answerID,
    rateCategory: e.target.value,
    userID: user.userID,
    createdDate: getCurrentDate(),
  };

  $.ajax({
    url: "../controllers/AnswerEvaluateController.php",
    type: "POST",
    data: { action: "addEvaluate", ...data },
    success: (data) => {
      alert(data);
    },
    error: (xhr, status, error) => {
      // Handle error response
      if (xhr.status === 400) {
        alert(xhr.responseText);
      } else {
        alert("An error occurred: " + error);
      }
    },
  });
};

const getCurrentDate = () => {
  const currentDate = new Date();

  const year = currentDate.getFullYear();
  const month = String(currentDate.getMonth() + 1).padStart(2, "0");
  const day = String(currentDate.getDate()).padStart(2, "0");
  const hours = String(currentDate.getHours()).padStart(2, "0");
  const minutes = String(currentDate.getMinutes()).padStart(2, "0");
  const seconds = String(currentDate.getSeconds()).padStart(2, "0");

  const formattedDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

  return formattedDate;
};

const handleRedirect = (e) => {
  const id = $(e.target).data("question-id");
  const page = $(e.target).data("page");
  const question = $(e.target).data("question");
  window.location.href = `${window.location.pathname}?page=${page}&id=${id}&question=${question}`;
};

const getParams = (url) => {
  const params = {};
  const parser = document.createElement("a");
  parser.href = url;
  const query = parser.search.substring(1);
  const vars = query.split("&");
  for (let i = 0; i < vars.length; i++) {
    const pair = vars[i].split("=");
    params[pair[0]] = decodeURIComponent(pair[1]);
  }
  return params;
};

const handleAddMenu = () => {
  let user = getUser() || { role: "Viewer" };

  // Hiển thị menu dựa trên vai trò của người dùng
  var links = [
    {
      link: "add-question",
      name: "Trang 1: Đặt câu hỏi",
      allowedRoles: ["Admin", "Questioner"],
    },
    {
      link: "list-questions",
      name: "Trang 2: Danh sách câu hỏi",
      allowedRoles: ["Admin", "Questioner", "Answerer", "Evaluater", "Viewer"],
    },
    {
      link: "last-answers",
      name: "Trang 4: Danh sách câu trả lời mới nhất",
      allowedRoles: ["Admin", "Evaluater", "Viewer"],
    },
    {
      link: "last-evaluates",
      name: "Trang 5: Danh sách các đánh giá mới nhất",
      allowedRoles: ["Admin", "Evaluater", "Viewer"],
    },
    {
      link: "change-role",
      name: "Trang 6: Đổi vai trò",
      allowedRoles: ["Admin", "Questioner", "Answerer", "Evaluater"],
    },
  ];

  var menuHTML = '<div><ul class="navbar-nav mr-auto d-flex flex-column">';

  for (var i = 0; i < links.length; i++) {
    var link = links[i];
    // Kiểm tra quyền truy cập dựa trên link.allowedRoles và userRole
    if (link.allowedRoles.includes(user.role)) {
      menuHTML +=
        '<li class="nav-item ' +
        (window.location.href.includes(link.link) ? "active" : "") +
        '">';
      menuHTML +=
        '<a class="nav-link" href="index.php?page=' +
        link.link +
        '">' +
        link.name +
        "</a>";
      menuHTML += "</li>";
    }
  }

  menuHTML += "</ul></div>";

  // Hiển thị menu
  $("#menu").append(menuHTML);
};
