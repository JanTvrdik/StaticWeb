<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 * @package Nette\Loaders
 */



/**
 * Nette auto loader is responsible for loading Nette classes and interfaces.
 *
 * @author     David Grudl
 */
class NetteLoader extends AutoLoader
{
	/** @var NetteLoader */
	private static $instance;

	/** @var array */
	public $list = array(
		'abortexception' => '/Application/Exceptions/AbortException.php',
		'ambiguousserviceexception' => '/Utils/AmbiguousServiceException.php',
		'annotation' => '/Reflection/Annotation.php',
		'annotationsparser' => '/Reflection/AnnotationsParser.php',
		'appform' => '/Application/AppForm.php',
		'application' => '/Application/Application.php',
		'applicationexception' => '/Application/Exceptions/ApplicationException.php',
		'argumentoutofrangeexception' => '/Utils/exceptions.php',
		'arraylist' => '/Utils/ArrayList.php',
		'arraytools' => '/Utils/ArrayTools.php',
		'authenticationexception' => '/Security/AuthenticationException.php',
		'autoloader' => '/Loaders/AutoLoader.php',
		'badrequestexception' => '/Application/Exceptions/BadRequestException.php',
		'badsignalexception' => '/Application/Exceptions/BadSignalException.php',
		'button' => '/Forms/Controls/Button.php',
		'cache' => '/Caching/Cache.php',
		'cachinghelper' => '/Templates/Filters/CachingHelper.php',
		'callback' => '/Utils/Callback.php',
		'callbackfilteriterator' => '/Utils/Iterators/CallbackFilterIterator.php',
		'checkbox' => '/Forms/Controls/Checkbox.php',
		'classreflection' => '/Reflection/ClassReflection.php',
		'clirouter' => '/Application/Routers/CliRouter.php',
		'component' => '/ComponentModel/Component.php',
		'componentcontainer' => '/ComponentModel/ComponentContainer.php',
		'config' => '/Config/Config.php',
		'configadapterini' => '/Config/ConfigAdapterIni.php',
		'configurator' => '/Environment/Configurator.php',
		'connection' => '/Database/Connection.php',
		'context' => '/Utils/Context.php',
		'control' => '/Application/Control.php',
		'databasepanel' => '/Database/DatabasePanel.php',
		'databasereflection' => '/Database/Reflection/DatabaseReflection.php',
		'datetime53' => '/Utils/DateTime53.php',
		'debug' => '/Debug/Debug.php',
		'debughelpers' => '/Debug/DebugHelpers.php',
		'debugpanel' => '/Debug/DebugPanel.php',
		'defaultformrenderer' => '/Forms/Renderers/DefaultFormRenderer.php',
		'deprecatedexception' => '/Utils/exceptions.php',
		'directorynotfoundexception' => '/Utils/exceptions.php',
		'downloadresponse' => '/Application/Responses/DownloadResponse.php',
		'dummystorage' => '/Caching/DummyStorage.php',
		'environment' => '/Environment/Environment.php',
		'extensionreflection' => '/Reflection/ExtensionReflection.php',
		'fatalerrorexception' => '/Utils/exceptions.php',
		'filejournal' => '/Caching/FileJournal.php',
		'filenotfoundexception' => '/Utils/exceptions.php',
		'filestorage' => '/Caching/FileStorage.php',
		'filetemplate' => '/Templates/FileTemplate.php',
		'fileupload' => '/Forms/Controls/FileUpload.php',
		'finder' => '/Utils/Finder.php',
		'forbiddenrequestexception' => '/Application/Exceptions/ForbiddenRequestException.php',
		'form' => '/Forms/Form.php',
		'formcontainer' => '/Forms/FormContainer.php',
		'formcontrol' => '/Forms/Controls/FormControl.php',
		'formgroup' => '/Forms/FormGroup.php',
		'forwardingresponse' => '/Application/Responses/ForwardingResponse.php',
		'framework' => '/Utils/Framework.php',
		'freezableobject' => '/Utils/FreezableObject.php',
		'functionreflection' => '/Reflection/FunctionReflection.php',
		'genericrecursiveiterator' => '/Utils/Iterators/GenericRecursiveIterator.php',
		'groupedtableselection' => '/Database/Selector/GroupedTableSelection.php',
		'hiddenfield' => '/Forms/Controls/HiddenField.php',
		'html' => '/Web/Html.php',
		'httpcontext' => '/Web/HttpContext.php',
		'httprequest' => '/Web/HttpRequest.php',
		'httprequestfactory' => '/Web/HttpRequestFactory.php',
		'httpresponse' => '/Web/HttpResponse.php',
		'httpuploadedfile' => '/Web/HttpUploadedFile.php',
		'iannotation' => '/Reflection/IAnnotation.php',
		'iauthenticator' => '/Security/IAuthenticator.php',
		'iauthorizator' => '/Security/IAuthorizator.php',
		'icachejournal' => '/Caching/ICacheJournal.php',
		'icachestorage' => '/Caching/ICacheStorage.php',
		'icomponent' => '/ComponentModel/IComponent.php',
		'icomponentcontainer' => '/ComponentModel/IComponentContainer.php',
		'iconfigadapter' => '/Config/IConfigAdapter.php',
		'icontext' => '/Utils/IContext.php',
		'idebugpanel' => '/Debug/IDebugPanel.php',
		'identity' => '/Security/Identity.php',
		'ifiletemplate' => '/Templates/IFileTemplate.php',
		'iformcontrol' => '/Forms/IFormControl.php',
		'iformrenderer' => '/Forms/IFormRenderer.php',
		'ifreezable' => '/Utils/IFreezable.php',
		'ihttprequest' => '/Web/IHttpRequest.php',
		'ihttpresponse' => '/Web/IHttpResponse.php',
		'iidentity' => '/Security/IIdentity.php',
		'image' => '/Utils/Image.php',
		'imagebutton' => '/Forms/Controls/ImageButton.php',
		'imagemagick' => '/Utils/ImageMagick.php',
		'imailer' => '/Mail/IMailer.php',
		'instancefilteriterator' => '/Utils/Iterators/InstanceFilterIterator.php',
		'invalidlinkexception' => '/Application/Exceptions/InvalidLinkException.php',
		'invalidpresenterexception' => '/Application/Exceptions/InvalidPresenterException.php',
		'invalidstateexception' => '/Utils/exceptions.php',
		'ioexception' => '/Utils/exceptions.php',
		'ipartiallyrenderable' => '/Application/IPartiallyRenderable.php',
		'ipresenter' => '/Application/IPresenter.php',
		'ipresenterloader' => '/Application/IPresenterLoader.php',
		'ipresenterresponse' => '/Application/IPresenterResponse.php',
		'irenderable' => '/Application/IRenderable.php',
		'iresource' => '/Security/IResource.php',
		'irole' => '/Security/IRole.php',
		'irouter' => '/Application/IRouter.php',
		'isignalreceiver' => '/Application/ISignalReceiver.php',
		'istatepersistent' => '/Application/IStatePersistent.php',
		'isubmittercontrol' => '/Forms/ISubmitterControl.php',
		'isupplementaldriver' => '/Database/ISupplementalDriver.php',
		'itemplate' => '/Templates/ITemplate.php',
		'itranslator' => '/Utils/ITranslator.php',
		'iuser' => '/Web/IUser.php',
		'json' => '/Utils/Json.php',
		'jsonexception' => '/Utils/JsonException.php',
		'jsonresponse' => '/Application/Responses/JsonResponse.php',
		'latteexception' => '/Templates/Filters/LatteException.php',
		'lattefilter' => '/Templates/Filters/LatteFilter.php',
		'lattemacros' => '/Templates/Filters/LatteMacros.php',
		'limitedscope' => '/Loaders/LimitedScope.php',
		'link' => '/Application/Link.php',
		'mail' => '/Mail/Mail.php',
		'mailmimepart' => '/Mail/MailMimePart.php',
		'mapiterator' => '/Utils/Iterators/MapIterator.php',
		'memberaccessexception' => '/Utils/exceptions.php',
		'memcachedstorage' => '/Caching/MemcachedStorage.php',
		'memorystorage' => '/Caching/MemoryStorage.php',
		'methodreflection' => '/Reflection/MethodReflection.php',
		'multirouter' => '/Application/Routers/MultiRouter.php',
		'multiselectbox' => '/Forms/Controls/MultiSelectBox.php',
		'nclosurefix' => '/Utils/Framework.php',
		'neonexception' => '/Utils/NeonException.php',
		'neonparser' => '/Utils/NeonParser.php',
		'netteloader' => '/Loaders/NetteLoader.php',
		'notimplementedexception' => '/Utils/exceptions.php',
		'notsupportedexception' => '/Utils/exceptions.php',
		'object' => '/Utils/Object.php',
		'objectmixin' => '/Utils/ObjectMixin.php',
		'paginator' => '/Utils/Paginator.php',
		'parameterreflection' => '/Reflection/ParameterReflection.php',
		'pdomssqldriver' => '/Database/Drivers/PdoMsSqlDriver.php',
		'pdomysqldriver' => '/Database/Drivers/PdoMySqlDriver.php',
		'pdoocidriver' => '/Database/Drivers/PdoOciDriver.php',
		'pdoodbcdriver' => '/Database/Drivers/PdoOdbcDriver.php',
		'pdopgsqldriver' => '/Database/Drivers/PdoPgSqlDriver.php',
		'pdosqlite2driver' => '/Database/Drivers/PdoSqlite2Driver.php',
		'pdosqlitedriver' => '/Database/Drivers/PdoSqliteDriver.php',
		'permission' => '/Security/Permission.php',
		'presenter' => '/Application/Presenter.php',
		'presentercomponent' => '/Application/PresenterComponent.php',
		'presentercomponentreflection' => '/Application/PresenterComponentReflection.php',
		'presenterloader' => '/Application/PresenterLoader.php',
		'presenterrequest' => '/Application/PresenterRequest.php',
		'propertyreflection' => '/Reflection/PropertyReflection.php',
		'radiolist' => '/Forms/Controls/RadioList.php',
		'recursivecallbackfilteriterator' => '/Utils/Iterators/RecursiveCallbackFilterIterator.php',
		'recursivecomponentiterator' => '/ComponentModel/RecursiveComponentIterator.php',
		'redirectingresponse' => '/Application/Responses/RedirectingResponse.php',
		'regexpexception' => '/Utils/RegexpException.php',
		'renderresponse' => '/Application/Responses/RenderResponse.php',
		'robotloader' => '/Loaders/RobotLoader.php',
		'route' => '/Application/Routers/Route.php',
		'routingdebugger' => '/Application/RoutingDebugger.php',
		'row' => '/Database/Row.php',
		'rule' => '/Forms/Rule.php',
		'rules' => '/Forms/Rules.php',
		'safestream' => '/Utils/SafeStream.php',
		'selectbox' => '/Forms/Controls/SelectBox.php',
		'sendmailmailer' => '/Mail/SendmailMailer.php',
		'session' => '/Web/Session.php',
		'sessionnamespace' => '/Web/SessionNamespace.php',
		'simpleauthenticator' => '/Security/SimpleAuthenticator.php',
		'simplerouter' => '/Application/Routers/SimpleRouter.php',
		'smartcachingiterator' => '/Utils/Iterators/SmartCachingIterator.php',
		'smtpexception' => '/Mail/SmtpException.php',
		'smtpmailer' => '/Mail/SmtpMailer.php',
		'sqlliteral' => '/Database/SqlLiteral.php',
		'sqlpreprocessor' => '/Database/SqlPreprocessor.php',
		'statement' => '/Database/Statement.php',
		'string' => '/Utils/String.php',
		'submitbutton' => '/Forms/Controls/SubmitButton.php',
		'tablerow' => '/Database/Selector/TableRow.php',
		'tableselection' => '/Database/Selector/TableSelection.php',
		'template' => '/Templates/Template.php',
		'templatecachestorage' => '/Templates/TemplateCacheStorage.php',
		'templateexception' => '/Templates/TemplateException.php',
		'templatefilters' => '/Templates/Filters/TemplateFilters.php',
		'templatehelpers' => '/Templates/Filters/TemplateHelpers.php',
		'textarea' => '/Forms/Controls/TextArea.php',
		'textbase' => '/Forms/Controls/TextBase.php',
		'textinput' => '/Forms/Controls/TextInput.php',
		'tokenizer' => '/Utils/Tokenizer.php',
		'tokenizerexception' => '/Utils/TokenizerException.php',
		'tools' => '/Utils/Tools.php',
		'uri' => '/Web/Uri.php',
		'uriscript' => '/Web/UriScript.php',
		'user' => '/Web/User.php',
	);



	/**
	 * Returns singleton instance with lazy instantiation.
	 * @return NetteLoader
	 */
	public static function getInstance()
	{
		if (self::$instance === NULL) {
			self::$instance = new self;
		}
		return self::$instance;
	}



	/**
	 * Handles autoloading of classes or interfaces.
	 * @param  string
	 * @return void
	 */
	public function tryLoad($type)
	{
		$type = ltrim(strtolower($type), '\\');
		if (isset($this->list[$type])) {
			LimitedScope::load(NETTE_DIR . $this->list[$type]);
			self::$count++;
		}
	}

}
