<a name="readme-top"></a>

<div align="center">

  <img src="https://cdn.alexishenry.eu/shared/images/logo.png" alt="logo" width="220" height="auto" />
  <h1>cdn.alexishenry.eu</h1>
  
  <p>
    My cdn deployer made with :heart:
  </p>

<a href="https://alxishenry.github.io/docs"><strong>Documentation »</strong></a>

<h4>
    <a href="https://cdn.alexishenry.eu">Go to the site</a>
  <span> · </span>
    <a href="https://github.com/AlxisHenry/cdn.alexishenry.eu/issues">Report a bug</a>
  <span> · </span>
    <a href="https://github.com/AlxisHenry/cdn.alexishenry.eu/issues">I have an idea</a>
  </h4>
</div>

<br />

# :notebook_with_decorative_cover: Summary

- [About the project](#star2-about-the-project)
  * [Techs](#space_invader-techs)
- [Getting Started](#toolbox-getting-started)
  * [Installation](#gear-setup)
- [Authors](#wave-authors)

## :star2: About the project

This project is built with Bash and is used to upload files to my cdn.

### :space_invader: Techs

[![Shell](https://img.shields.io/badge/bash%20-hotpink.svg?&style=for-the-badge&logo=gnu-bash&logoColor=4EAA25&color=gray)]()

## :toolbox: Getting Started

### :gear: Setup

**Clone the repository**

```bash
git clone -b sync https://github.com/AlxisHenry/cdn.git
```

**Deployement**

*Don't forgot to configure your enviroments variables and place the files you want to deploy in the `uploads` folder.*

```bash
bash cdn.sh
```

Following options are available:

- `-h`, or `--help` : Display the help.
- `-u`, or `--update` : Check for updates.
- `-t`, or `--tests` : Run the tests.
- `-c`, or `--config` : Setup configuration.
- `-d`, or `--debug` : Display the debug messages.
- `-f`, or `--force` : Force the upload of the files (skip some checks).

```bash
Usage: cdn.sh [options]
```

*For more information, please refer to the [Documentation](https://alxishenry.github.io/docs).*

## :wave: Authors

* **Alexis Henry** _alias_ [@AlxisHenry](https://github.com/AlxisHenry)

<!-- ## :page_with_curl: Liens utiles -->

<p align="right">(<a href="#readme-top">back to top</a>)</p>
