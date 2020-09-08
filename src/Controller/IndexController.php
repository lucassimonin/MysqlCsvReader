<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utils\MysqlCsvToolsInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 *
 * @package   App\Controller
 * @author    Lucas SIMONIN <lsimonin2@gmail.com>
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://lucasimonin.me/
 */
class IndexController extends AbstractController
{
    /**
     * Description $mysqlCsvTools field
     *
     * @var MysqlCsvToolsInterface $mysqlCsvTools
     */
    protected MysqlCsvToolsInterface $mysqlCsvTools;

    /**
     * IndexController constructor
     *
     * @param MysqlCsvToolsInterface $mysqlCsvTools
     */
    public function __construct(
      MysqlCsvToolsInterface $mysqlCsvTools
    ) {
        $this->mysqlCsvTools = $mysqlCsvTools;
    }

    /**
     * Description index function
     *
     * @Route("/", name="homepage")
     *
     * @return Response
     */
    public function index(): Response
    {
        $this->mysqlCsvTools->generateTable('test2', 'sample.csv');
        // todo UI
        $string = $this->mysqlCsvTools->loadDataCsv('test2', 'sample.csv');
        return $this->render('index/index.html.twig', [
            'controller_name' => $string,
        ]);
    }
}
