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

    const AI_NAMESPACE = 'sat8bit\RoombaSim\Roomba\\';

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
        $ai = $this->getAIObject($input->getArgument('ai'));

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

        // create application
        $app = new Application($room, $roomba, $view);

        $result = $app->run((int)$input->getOption("step"));

        echo "Result : " . ($result ? "Cleaned($result)" : "Not cleaned.") . "\n";
    }

    /**
     * load ai class.
     *
     * @param string $className
     * @return RoombaAIInterface
     */
    protected function getAIObject($className)
    {
        $object = $this->getObject($className, self::AI_NAMESPACE);
        if (!($object instanceof Roomba\RoombaAIInterface)) {
            throw new \InvalidArgumentException("$className is not implements sat8bit\RoombaSim\Roomba\RoombaAIInterface");
        }

        return $object;
    }

    /**
     * load room class.
     *
     * @param string $className
     * @return AbstractRoom
     */
    protected function getRoomObject($className)
    {
        $object = $this->getObject($className, self::ROOM_NAMESPACE);
        if (!($object instanceof Room\AbstractRoom)) {
            throw new \InvalidArgumentException("$className is not extends sat8bit\RoombaSim\Room\AbstractRoom");
        }

        return $object;
    }

    /**
     * load class.
     *
     * @param string $className
     * @return AbstractRoom
     */
    protected function getObject($className, $namespace)
    {
        if (class_exists($className)) {
            return new $className();
        }

        if (class_exists($namespace . $className)) {
            $className = $namespace . $className;
            return new $className();
        }

        throw new \InvalidArgumentException("class is not exists: $className");
    }
}
