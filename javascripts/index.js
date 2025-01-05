document.addEventListener("DOMContentLoaded", () => {
  console.log("Document loaded and script running"); // Check if script is running

  const register = document.getElementById("signup-form");

  register.addEventListener("click", async (e) => {
    e.preventDefault();
    console.log("register form submitted");

    const fullName = document.getElementById("fullName").value;
    const phoneNo = document.getElementById("phoneNo").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    if (!fullName || !phoneNo || !email || !password) {
      alert("Please fill in all fields");
      return;
    }

    console.log("Full Name: ", fullName);
    console.log("Phone No.: ", phoneNo);
    console.log("Email: ", email);
    console.log("Password: ", password);

    try {
      const response = await fetch("http://localhost:5000/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          fullName,
          phoneNo,
          email,
          password,
        }),
      });

      if (response.ok) {
        alert("User registered successfully");
        window.location.href = "login.html";
      } else {
        alert("User registration failed");
        window.location.href = "signup.html";
      }
    } catch (error) {
      console.error("Error during registration:", error);
      alert("An error occurred. Please try again.");
    }
  });

  const login = document.getElementById("login-form");
  login.addEventListener("submit", async (e) => {
    e.preventDefault();
    console.log("login form submitted");

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    if (!email || !password) {
      alert("Please fill in all fields");
      return;
    }
    if (email === "admin@gmail.com" && password === "admin") {
      alert("Admin logged in successfully");
      window.location.href = "/admin-dashboard/admin.html";
    }
    console.log("Email: ", email);
    console.log("Password: ", password);

    try {
      const response = await fetch("http://localhost:5000/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          email,
          password,
        }),
      });

      if (response.ok) {
        alert("User logged in successfully");
        window.location.href = "dashboard.html";
      } else {
        alert("User login failed");
        window.location.href = "login.html";
      }
    } catch (error) {
      console.error("Error during login:", error);
      alert("An error occurred. Please try again.");
    }
  });
});
