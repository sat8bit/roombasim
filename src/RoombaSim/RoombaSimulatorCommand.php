<?php

namespace sat8bit\RoombaSim;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use sat8bit\RoombaSim\Application;
use sat8bit\RoombaSim\Roomba;
use sat8bit\RoombaSim\Room\AbstractRoom;
use sat8bit\RoombaSim\Coordinate;

class RoombaSimulatorCommand extends Command
{
    const DEFAULT_STEP = 10000;

    const ROOM_NAMESPACE = 'sat8bit\RoombaSim\Room\\';
    const DEFAULT_ROOM = 'RectangleRoom15x15';

    protected function configure()
    {
        $this
            ->setName('roombasim')
            ->setDescription('roomba simurator')
            ->addArgument(
                'ai',
                InputArgument::REQUIRED,
                'Enter the name of RoombaAI that implements the sat8bit\Roomba\RoombaAIInterface the full path.'
            )
            ->addOption(
                'step',
                null,
                InputOption::VALUE_REQUIRED,
                'step number.',
                self::DEFAULT_STEP
            )
            ->addOption(
                'disp',
                null,
                InputOption::VALUE_NONE,
                'display by step.'
            )
            ->addOption(
                'room',
                null,
                InputOption::VALUE_REQUIRED,
                'Enter the name of Room that extends the sat8bit\RoombaSim\Room\AbstractRoom the full path.',
                self::DEFAULT_ROOM
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // create ai
        $aiClassName = $input->getArgument('ai');
        if (!class_exists($aiClassName)) {
            throw new \InvalidArgumentException("class is not exists: $aiClassName");
        }
        $ai = new $aiClassName();

        if (!($ai instanceof Roomba\RoombaAIInterface)) {
            throw new \InvalidArgumentException("$className is not implements sat8bit\RoombaSim\Roomba\RoombaAIInterface");
        }
        // create roomba
        $roomba = new Roomba\Roomba(new Coordinate(0, 0), new Roomba\Direction(), $ai);

        // create room
        $room = $this->getRoomObject($input->getOption('room'));

        // create view
        if ($input->getOption("disp")) {
            $view = new View\ConsoleView();
        } else { 
            $view = new View\BlackHoleView();
        }

        // application
        $app = new Application($room, $roomba, $view);

        $step = (int)$input->getOption("step");
        $result = $app->run($step);

        echo "Result : " . ($result ? "Cleaned($result)" : "Not cleaned.") . "\n";
    }

    /**
     * load class.
     *
     * @param string $className
     * @return AbstractRoom
     */
    protected function getRoomObject($className)
    {
        if (class_exists($className)) {
            return new $roomClassName();
        }

        if (class_exists(self::ROOM_NAMESPACE . $className)) {
            $className = self::ROOM_NAMESPACE . $className;
            return new $className();
        }

        throw new \InvalidArgumentException("class is not exists: $className");
    }
}
