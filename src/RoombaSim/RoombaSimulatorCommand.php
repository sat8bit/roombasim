<?php

namespace sat8bit\RoombaSim;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use sat8bit\RoombaSim\Application;
use sat8bit\RoombaSim\Roomba;
use sat8bit\RoombaSim\Room\Room;
use sat8bit\RoombaSim\Coordinate;

class RoombaSimulatorCommand extends Command
{
    const DEFAULT_STEP = 10000;

    const DEFAULT_ROOM = "20x20";

    const DEFAULT_DIRT = 1;

    protected function configure()
    {
        $this
            ->setName('roombasim')
            ->setDescription('roomba simurator')
            ->addArgument(
                'ai-class-name',
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
                'Enter the size of the room.',
                self::DEFAULT_ROOM
            )
            ->addOption(
                'dirt',
                null,
                InputOption::VALUE_REQUIRED,
                'Enter the dirt of the room.',
                self::DEFAULT_DIRT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // create ai
        $className = $input->getArgument('ai-class-name');
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("class is not exists: $className");
        }
        $ai = new $className();

        if (!($ai instanceof Roomba\RoombaAIInterface)) {
            throw new \InvalidArgumentException("$className is not implements sat8bit\RoombaSim\Roomba\RoombaAIInterface");
        }

        // create roomba
        $roomba = new Roomba\Roomba(new Coordinate(1, 1), new Roomba\Direction(), $ai);
        // create room
        $roomOpt = $input->getOption('room');
        $roomSize = explode("x", $roomOpt);
        if (count($roomSize) != 2) {
            throw new \InvalidArgumentException("Invalid room option: $roomOpt");
        }
        $width = (int)$roomSize[0];
        $height = (int)$roomSize[1];
        $dirt = (int)$input->getOption('dirt');
        $room = new Room($height, $width, $dirt);

        $app = new Application($room, $roomba);

        $step = (int)$input->getOption("step");
        if ($input->getOption("disp")) {
            $result = $app->runWithDisp($step);
        } else { 
            $result = $app->run($step);
        }

        echo "Result : " . ($result ? "Cleaned($result)" : "Not cleaned.") . "\n";
    }
}
