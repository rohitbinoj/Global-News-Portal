const apiKey = "df08231bb4af4226940698580d38e0fa";
const newsContainer = document.getElementById("news-container");
const categoryButtons = document.querySelectorAll(".category-btn");
const searchButton = document.querySelector(".searchbutton");
const searchInput = document.querySelector(".search");
const usernameSpan = document.getElementById("username");
const logoutBtn = document.getElementById("logout-btn");

function checkAuth() {
    const username = sessionStorage.getItem("username");
    if (!username) {
        window.location.href = "login.html";
    } else {
        usernameSpan.textContent = username;
    }
}

function logout() {
    sessionStorage.removeItem("username");
    window.location.href = "login.html";
}

if (logoutBtn) {
    logoutBtn.addEventListener("click", logout);
}

async function fetchNews(category = "general", query = "") {
    try {
        newsContainer.innerHTML = '<div class="loader">Loading...</div>';
        let url = `https://newsapi.org/v2/top-headlines?country=us&category=${category}&apiKey=${apiKey}`;
        if (query) {
            url = `https://newsapi.org/v2/everything?q=${query}&apiKey=${apiKey}`;
        }
        const response = await axios.get(url);
        const articles = response.data.articles;
        displayNews(articles);
    } catch (error) {
        console.error("Error fetching news:", error);
        newsContainer.innerHTML = "<p>An error occurred while fetching news. Please try again later.</p>";
    }
}

function displayNews(articles) {
    newsContainer.innerHTML = "";
    articles.forEach((article) => {
        const newsItem = document.createElement("div");
        newsItem.classList.add("news-item");
        newsItem.innerHTML = `
            <img src="${article.urlToImage || "/api/placeholder/300/200"}" alt="${article.title}" class="news-image">
            <div class="news-content">
                <h2 class="news-title">${article.title}</h2>
                <p class="news-description">${article.description || "No description available."}</p>
                <p class="news-source">Source: ${article.source.name}</p>
            </div>
        `;
        newsItem.addEventListener("click", () => {
            window.open(article.url, "_blank");
        });
        newsContainer.appendChild(newsItem);
    });
}

if (categoryButtons) {
    categoryButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const category = button.getAttribute("data-category");
            fetchNews(category);
        });
    });
}

if (searchButton) {
    searchButton.addEventListener("click", () => {
        const query = searchInput.value.trim();
        if (query) {
            fetchNews("", query);
        }
    });
}

const loginForm = document.getElementById('login-form');
if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        try {
            const response = await axios.post('login.php', { username, password });
            if (response.data === "Login successful") {
                sessionStorage.setItem('username', username);
                window.location.href = 'news.html';
            } else {
                alert(response.data);
            }
        } catch (error) {
            console.error('Login error:', error);
            alert('An error occurred during login. Please try again.');
        }
    });
}

const registerForm = document.getElementById('register-form');
if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const name = document.getElementById('name').value;
        const age = document.getElementById('age').value;
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            const response = await axios.post('registration.php', {
                name,
                age,
                username,
                email,
                password
            });
            if (response.data === "Registration successful") {
                alert('Registration successful! Please log in.');
                window.location.href = 'login.html';
            } else {
                alert(response.data);
            }
        } catch (error) {
            console.error('Registration error:', error);
            alert('An error occurred during registration. Please try again.');
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    if (window.location.pathname.includes('news.html')) {
        checkAuth();
        fetchNews();
    }
});
      
