TechGuide - helps when every device looks the same
============

[![Build Status](https://api.travis-ci.org/nfqakademija/tech-guide.svg?branch=master)](https://travis-ci.org/nfqakademija/tech-guide)
[![Symfony](https://img.shields.io/badge/Symfony-%204.x-green.svg "Supports Symfony 4.x")](https://symfony.com/)

# Table of Contents

* [Project Description](#project-description)
* [Requirements](#requirements)
* [How to Run?](#how-to-run)
* [Team Members](#team-members)

# <a name="project-description"></a>Project Description

Nowadays, technologies take an important aspect of our lifes. However, many people don\`t know  or understand their capabilities and often choose to buy products by their brand or friend experiences. **TechGuide** can help them! All a person has to do is just answer some basic questions about his/her life and **TechGuide** converts them to device offers!
 
**And that is It!**

**TechGuide** - developed to help people in a world full of sophisticated devices.

# <a name="requirements"></a>Requirements

* docker: `>=18.x-ce` 
* docker-compose: `>=1.20.1`

# <a name="how-to-run"></a>How to Run dev environment:

```bash
  $ git clone <project>
  $ cd path/to/<project>
  $ ./provision.sh --schema --with-fixtures 
```

* `--schema` - used to recreate project's database;
* `--with-fixtures` - generates demo data (will not work without `--schema`); 

Open `http://127.0.0.1:8000/`;

# <a name="team-members"></a>Team Members

#### Mentor:

* Sergej Voronov

#### Developers:

* Martynas Druteika
* Matas Šilinskas
