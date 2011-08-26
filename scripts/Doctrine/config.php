<?php
/*
 *  $Id: config.php 2753 2007-10-07 20:58:08Z Jonathan.Wage $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine Configuration File
 *
 * This is a sample implementation of Doctrine
 * 
 * @package     Doctrine
 * @subpackage  Config
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision: 2753 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @author      Jonathan H. Wage <jwage@mac.com>
 */

define('SANDBOX_PATH', dirname(__FILE__));
define('SYSTEM',dirname(dirname(SANDBOX_PATH)) . DIRECTORY_SEPARATOR . 'src/system');
define('DOCTRINE_PATH', SYSTEM . '/library/Doctrine');
define('DATA_FIXTURES_PATH', SYSTEM . '/configs/data/fixtures');
define('MODELS_PATH', SYSTEM . DIRECTORY_SEPARATOR . 'models');
define('MIGRATIONS_PATH', SYSTEM . '/configs/data/migrations');
define('SQL_PATH', SYSTEM . '/configs/data/sql');
define('YAML_SCHEMA_PATH', SYSTEM . DIRECTORY_SEPARATOR . '/configs/data/schema');

// Include Yaml Parser
require_once(SYSTEM.'/library/sfYaml/sfYaml.php');
// Load Application Config
$config = sfYaml::load(SYSTEM.'/configs/config.yml');


require_once(SYSTEM . DIRECTORY_SEPARATOR . '/library/Doctrine.php');

Doctrine_Core::setExtensionsPath(SYSTEM.'/library/DoctrineExtensions');

spl_autoload_register(array('Doctrine', 'autoload'));
spl_autoload_register(array('Doctrine', 'modelsAutoload'));
spl_autoload_register(array('Doctrine', 'extensionsAutoload'));

$manager = Doctrine_Manager::getInstance();
$manager->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
$manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
$manager->setAttribute( Doctrine_Core::ATTR_USE_NATIVE_ENUM, true );
$manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_PEAR);
$manager->setAttribute(Doctrine_Core::ATTR_TABLE_CLASS_FORMAT, 'Table_%s');
$manager->setAttribute( Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES,true);
$manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
$manager->setCharset( 'utf8' );
$manager->setCollate( 'utf8_unicode_ci' );
// Set DSN
if($config['database']['phptype'] == 'sqlite'){
  $config['database']['dsn'] = "sqlite:///".SYSTEM."/cache/".$config['database']['database'].".sqlite?mode=666";
}else{
  $config['database']['dsn'] = $config['database']['phptype'] . 
                        '://' . $config['database']['username'] . 
                        ':' . $config['database']['password']. 
                        '@' . $config['database']['hostspec'] . 
                        '/' . $config['database']['database'] .
                        '?' . http_build_query($config['database']['options'], '', '&');
}
// Connect to database
$conn = Doctrine_Manager::connection($config['database']['dsn']);

Doctrine_Core::setModelsDirectory(MODELS_PATH);