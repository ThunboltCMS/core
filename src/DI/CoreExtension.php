<?php

namespace Thunbolt\DI;

use Nette\DI\CompilerExtension;
use Thunbolt\Forms\FormCase;
use Thunbolt\Grid\GridCase;
use Thunbolt\Grid\GridFactory;

class CoreExtension extends CompilerExtension {

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('form'))
			->setClass(FormCase::class);

		$builder->addDefinition($this->prefix('grid'))
			->setClass(GridCase::class);

		$builder->addDefinition($this->prefix('grid.factory'))
			->setClass(GridFactory::class);
	}

}