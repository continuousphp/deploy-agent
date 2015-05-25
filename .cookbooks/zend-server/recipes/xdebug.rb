include_recipe "zend-server::server"

package "automake"
package "make"

execute "Installing xdebug pecl extension" do
  command "/usr/local/zend/bin/pecl install xdebug"
  action :run
  not_if { ::File.exists?("/usr/local/zend/lib/php_extensions/xdebug.so") }
end

conf_plain_file '/usr/local/zend/etc/php.ini' do
  pattern  /xdebug/
  new_line 'zend_extension="/usr/local/zend/lib/php_extensions/xdebug.so"'
  action   :insert_if_no_match
end

conf_plain_file '/usr/local/zend/etc/php.ini' do
  pattern  /max_nesting_level/
  new_line 'xdebug.max_nesting_level=200'
  action   :insert_if_no_match
end