const defuser = "blogadmin";
const defpass = "112233";

const logForm = document.querySelector("form");
const userName = document.getElementById("user");
const passWord = document.getElementById("pass");

logForm.addEventListener("submit", function (e) {
  e.preventDefault();

  const user = userName.value.trim();
  const pass = passWord.value.trim();

  if (user === defuser && pass === defpass) {
    alert("Login Succesfull");

    // localStorage.setItem("LoggedIn", "true");

    setTimeout(() => {
      window.location.href = "./dashboard.php";
    }, 1000);
  } else {
    alert("Gagal");

    passWord.value = "";
  }
});
