package "vim"

template "/etc/profile.d/sandbox.sh" do
  source "sandbox.erb"
  mode 0644
end
