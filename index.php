<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Near-Earth Rocks</title>
    <style>
      /* Minimalist styling */
      body {
        font-family: sans-serif;
        line-height: 1.5;
        padding: 20px;
      }
      .asteroid {
        border-bottom: 1px solid #ccc;
        padding: 10px 0;
      }
      .hazard {
        color: red;
        font-weight: bold;
      }
      #loading {
        font-style: italic;
      }
    </style>
  </head>
  <body>
    <h1>Rocks Close to Earth Today</h1>
    <div id="loading">Fetching data from NASA...</div>
    <div id="asteroid-list"></div>

    <script>
      // Replace 'DEMO_KEY' with your actual key from api.nasa.gov
      const API_KEY = "eJ8jKeUlWbPlrgq2Ih0f2nhtYshzyuFuQbYbdncx";
      const today = new Date().toISOString().split("T")[0];
      const url = `https://nasa.gov{today}&end_date=${today}&api_key=${API_KEY}`;

      async function getAsteroids() {
        try {
          const response = await fetch(url);
          const data = await response.json();
          const container = document.getElementById("asteroid-list");
          document.getElementById("loading").style.display = "none";

          // NeoWs returns data grouped by date strings
          const asteroids = data.near_earth_objects[today];

          asteroids.forEach((rock) => {
            const div = document.createElement("div");
            div.className = "asteroid";

            const isHazard = rock.is_potentially_hazardous_asteroid
              ? '<span class="hazard">[POTENTIALLY HAZARDOUS]</span>'
              : "";

            div.innerHTML = `
                        <strong>Name:</strong> ${rock.name} ${isHazard}<br>
                        <strong>Diameter (max):</strong> ${rock.estimated_diameter.meters.estimated_diameter_max.toFixed(
                          2
                        )} meters<br>
                        <strong>Miss Distance:</strong> ${Math.round(
                          rock.close_approach_data[0].miss_distance.kilometers
                        )} km<br>
                        <strong>Velocity:</strong> ${Math.round(
                          rock.close_approach_data[0].relative_velocity
                            .kilometers_per_hour
                        )} km/h
                    `;
            container.appendChild(div);
          });
        } catch (error) {
          document.getElementById("loading").innerText = "Error loading data.";
          console.error(error);
        }
      }

      getAsteroids();
    </script>
  </body>
</html>