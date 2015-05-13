#
# Cookbook Name:: postgresql
# Recipe:: setup_users
#

# setup users
node["postgresql"]["users"].each do |user|
  postgresql_user user["username"] do
    superuser user["superuser"]
    createdb  user["createdb"]
    login     user["login"]
    password  user["password"]
    encrypted_password user["encrypted_password"]
    action Array(user["action"] || "create").map(&:to_sym)
  end
end

postgresql_user "xmgt" do
  superuser false
  createdb false
  login true
  replication false
  password "secret"
end

postgresql_database "xmgt" do
  owner "xmgt"
  encoding "UTF-8"
  template "template0"
  locale "en_US.UTF-8"
end