<a name="readme-top"></a>

<div align="center">

  <img src="https://cdn.alexishenry.eu/shared/images/logo.png" alt="logo" width="220" height="auto" />
  <h1>cdn.alexishenry.eu</h1>
  
  <p>
    My cdn made with :heart:
  </p>

<a href="https://alxishenry.github.io/docs"><strong>Documentation »</strong></a>

<h4>
    <a href="https://alexishenry.eu">Go to the site</a>
  <span> · </span>
    <a href="https://github.com/AlxisHenry/cdn/issues">Report a bug</a>
  <span> · </span>
    <a href="https://github.com/AlxisHenry/cdn/issues">I have an idea</a>
  </h4>
</div>

<br />

# :notebook_with_decorative_cover: Summary

- [:notebook\_with\_decorative\_cover: About the project](#star2-about-the-project)
  * [:space\_invader: Techs](#space_invader-techs)
- [:toolbox: Getting Started](#toolbox-getting-started)
  * [:gear: Setup](#gear-setup)
  * [:gear: Configuration](#gear-config)
  * [:test\_tube: Tests](#test_tube-tests)
  * [:gear: Crontab](#gear-crontab)
- [:wave: Contributors](#wave-contributors)

## :star2: About the project

This project is built with PHP. It's a simple cdn to store my images and files.

### :space_invader: Techs

[![Php](https://img.shields.io/badge/php%20-%23323330.svg?&style=for-the-badge&logo=php&logoColor=8b9ed6&color=gray)]()
[![Shell](https://img.shields.io/badge/bash%20-hotpink.svg?&style=for-the-badge&logo=gnu-bash&logoColor=4EAA25&color=gray)]()

## :toolbox: Getting Started

### :gear: Setup

**Clone the repository**

```
git clone https://github.com/AlxisHenry/cdn.git
```

**Launch the setup script, it will do most of the work for you.**

```
bash setup.sh
```

### :gear: Configuration

The first step is to configure dashboard settings. You can do it by editing the `settings.yml` file situated in the root of the project.

```yml
dashboard:
  title: "CDN - IT'S ME"
  description: "COOL CDN FOR COOL PEOPLE"
```

The second step is to configure the `config.php` file. You can do it by editing the `config.php` file situated in the root of the project.

**In this file you can configure some settings like the environment, the maximum file size, etc...**

```php
return [
	/**
	 * Environment settings
	 */
	'APP_ENV' => 'development',

	/**
	 * Files settings
	 */
	'MAX_FILE_SIZE' => "50000000", // value in bytes (default: 50MB)
];
```

### :test_tube: Tests

**Run linters**

```
make lint
```

**Run the tests using the following command**

```
make tests || npm run tests
```

### :gear: Crontab

**To setup a CRON to save your shared files, run the command below:**

```bash
sudo bash utils/cron.sh || make cron
```

## :wave: Contributors

* **Alexis Henry** _alias_ [@AlxisHenry](https://github.com/AlxisHenry)

<!-- ## :page_with_curl: Liens utiles -->

<p align="right">(<a href="#readme-top">back to top</a>)</p>
