
CREATE TABLE IF NOT EXISTS build (
  repo_name VARCHAR(150) NOT NULL,
  build_id VARCHAR(150) NOT NULL,
  build_date INT NOT NULL,
  PRIMARY KEY (build_id),
  FOREIGN KEY (repo_name) REFERENCES project(repo_name));


CREATE TABLE IF NOT EXISTS exclude_folder (
  repo_name VARCHAR(150) NOT NULL,
  path VARCHAR(150) NOT NULL,
  PRIMARY KEY (repo_name,path),
  FOREIGN KEY (repo_name) REFERENCES project(repo_name));


CREATE TABLE IF NOT EXISTS phing_parameter (
  repo_name VARCHAR(150) NOT NULL,
  prop_name VARCHAR(50) NOT NULL,
  prop_value VARCHAR(50) NOT NULL,
  PRIMARY KEY (repo_name,prop_name),
  FOREIGN KEY (repo_name) REFERENCES project(repo_name));


CREATE TABLE IF NOT EXISTS project (
  repo_name VARCHAR(150) NOT NULL,
  project_name VARCHAR(150) NOT NULL,
  PRIMARY KEY (repo_name));


CREATE TABLE IF NOT EXISTS settings (
  build_path VARCHAR(150) NOT NULL,
  project_path VARCHAR(150) NOT NULL);