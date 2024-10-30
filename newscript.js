const apiKey = "0572e2afd61747b5bfb78ec4847fb573"; 
const newsContainer = document.getElementById("news-container");
const categoryButtons = document.querySelectorAll(".category-btn");
const searchButton = document.querySelector(".searchbutton");
const searchInput = document.querySelector(".search");

function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
  return array;
}

async function fetchPreferences() {
  try {
    const response = await fetch("get_preferences.php");
    const data = await response.json();
    return data.preferences ? data.preferences.split(",") : [];
  } catch (error) {
    console.error("Error fetching preferences:", error);
    return [];
  }
}

async function fetchNewsForCategory(category) {
  try {
    const url = `https://newsapi.org/v2/top-headlines?country=us&category=${category}&pageSize=5&apiKey=${apiKey}`;
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    return data.articles;
  } catch (error) {
    console.error(`Error fetching ${category} news:`, error);
    return [];
  }
}

async function fetchPreferredNews() {
  try {
    const preferences = await fetchPreferences();
    if (preferences.length === 0) {
      return await fetchNews("general");
    }

    let allArticles = [];
    for (const category of preferences) {
      const articles = await fetchNewsForCategory(category);
      allArticles = [...allArticles, ...articles];
    }

    displayNews(shuffleArray(allArticles));
  } catch (error) {
    console.error("Error fetching preferred news:", error);
    newsContainer.innerHTML =
      "<p>An error occurred while fetching news. Please try again later.</p>";
  }
}

async function fetchNews(category = "general", query = "") {
  try {
    newsContainer.innerHTML = '<div class="loader">Loading...</div>';
    let url;
    if (query) {
      url = `https://newsapi.org/v2/everything?q=${query}&apiKey=${apiKey}`;
    } else {
      url = `https://newsapi.org/v2/top-headlines?country=us&category=${category}&apiKey=${apiKey}`;
    }
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    displayNews(data.articles);
  } catch (error) {
    console.error("Error fetching news:", error);
    console.error("Error details:", error.message);
    newsContainer.innerHTML =
      "<p>An error occurred while fetching news. Please try again later.</p>";
  }
}

function displayNews(articles) {
  newsContainer.innerHTML = "";

  const validArticles = articles.filter((article) => {
    return !(
      article.title === "[Removed]" ||
      article.description === "[Removed]" ||
      article.source.name === "[Removed]" ||
      article.title == null ||
      article.description == null ||
      article.source.name == null
    );
  });

  if (validArticles.length === 0) {
    newsContainer.innerHTML =
      "<p>No news articles available at the moment. Please try again later.</p>";
    return;
  }

  validArticles.forEach((article) => {
    const newsItem = document.createElement("div");
    newsItem.classList.add("news-item");
    newsItem.innerHTML = `
            <img src="${
              article.urlToImage || "/api/placeholder/300/200"
            }" alt="${article.title}" class="news-image">
            <div class="news-content">
                <h2 class="news-title">${article.title}</h2>
                <p class="news-description">${
                  article.description || "No description available."
                }</p>
                <p class="news-source">Source: ${article.source.name}</p>
            </div>
        `;
    newsItem.addEventListener("click", () => {
      window.open(article.url, "_blank");
    });
    newsContainer.appendChild(newsItem);
  });
}

document.addEventListener("DOMContentLoaded", () => {
  fetchPreferredNews();

  categoryButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const category = button.getAttribute("data-category");
      if (category === "preferences") {
        fetchPreferredNews();
      } else {
        fetchNews(category);
      }
    });
  });

  if (searchButton) {
    searchButton.addEventListener("click", () => {
      const query = searchInput.value.trim();
      if (query) {
        saveSearchHistory(query);
        fetchNews("", query);
      }
    });
  }
});

function saveSearchHistory(query) {
  const formData = new URLSearchParams();
  formData.append("search_term", query);

  fetch("save_search.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Search history saved:", data);
    })
    .catch((error) => {
      console.error("Error saving search history:", error);
    });
}

function logout() {
  console.log("Logout button clicked");
  window.location.href = "logout.php";
}
