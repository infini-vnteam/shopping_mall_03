<?php
namespace Admin\Service;

use Application\Entity\Order;
use Application\Entity\User;
use Application\Entity\Review;
use Zend\Filter\StaticFilter;

class StatisticManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getOrderStatistic($type)
    {
        if ($type == 1)
            return $this->makeOrderDataChartByMonth();
        else return $this->makeOrderDataChartByYear();
    }

    private function makeOrderDataChartByMonth()
    {
        $data_week[0] = $this->getOrderQuantityByWeek(1)[0][1];
        $data_week[1] = $this->getOrderQuantityByWeek(8)[0][1];
        $data_week[2] = $this->getOrderQuantityByWeek(15)[0][1];
        $data_week[3] = $this->getOrderQuantityByWeek(22)[0][1];

        $data = $this->getPreConfigChart('orders');
        $data['subtitle']['text'] = 'Order By Month';
        $data['xAxis']['categories'] = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        $data['series'][0]['data'] = $data_week;

        return $data;
    }

    private function makeOrderDataChartByYear()
    {
        for ($i=0; $i < 12 ; $i++) { 
            $data_month[$i] = $this->getOrderQuantityByMonth($i + 1)[0][1];
        }

        $data = $this->getPreConfigChart('orders');
        $data['subtitle']['text'] = 'Order By Year';
        $data['xAxis']['categories'] = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data['series'][0]['data'] = $data_month;

        return $data;
    }

    private function getOrderQuantityByMonth($month)
    {
        $currentYear = date("Y");
        $currentMonth = $currentYear."-".$month."-01";
        $nextMonth = date("Y-m-d", strtotime("+1 month", strtotime($currentMonth)));
        
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('count(o.date_created)')
            ->from(Order::class, 'o')
            ->where('o.date_created >= :month')
            ->andWhere('o.date_created < :nextmonth')
            ->setParameter('month', $currentMonth)
            ->setParameter('nextmonth', $nextMonth);

        return $queryBuilder->getQuery()->getResult();
    }

    private function getOrderQuantityByWeek($day)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        
        $currentMonth = date("Y-m");
        $currentWeekDay = $currentMonth."-".$day;
        if ($day == 22){
            $currentMonth = date("Y-m")."-01";
            $currentMonth = strtotime($currentMonth);
            $nextWeekDay = date("Y-m-d", strtotime("+1 month", $currentMonth));
        }
        else $nextWeekDay = $currentMonth."-".($day+7);

        $queryBuilder->select('count(o.date_created)')
            ->from(Order::class, 'o')
            ->where('o.date_created >= :weekday')
            ->andWhere('o.date_created < :nextweek')
            ->setParameter('weekday', $currentWeekDay)
            ->setParameter('nextweek', $nextWeekDay);

        return $queryBuilder->getQuery()->getResult();
    }

    // review Chart 
    public function getReviewStatistic($type)
    {
        if ($type == 1)
            return $this->makeReviewDataChartByMonth();
        else return $this->makeReviewDataChartByYear();
    }

    private function makeReviewDataChartByMonth()
    {
        $data_week[0] = $this->getReviewQuantityByWeek(1)[0][1];
        $data_week[1] = $this->getReviewQuantityByWeek(8)[0][1];
        $data_week[2] = $this->getReviewQuantityByWeek(15)[0][1];
        $data_week[3] = $this->getReviewQuantityByWeek(22)[0][1];

        $data = $this->getPreConfigChart('reviews');
        $data['subtitle']['text'] = 'Review By Month';
        $data['xAxis']['categories'] = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        $data['series'][0]['data'] = $data_week;

        return $data;
    }

    private function makeReviewDataChartByYear()
    {
        for ($i=0; $i < 12 ; $i++) { 
            $data_month[$i] = $this->getReviewQuantityByMonth($i + 1)[0][1];
        }

        $data = $this->getPreConfigChart('reviews');
        $data['subtitle']['text'] = 'Review By Year';
        $data['xAxis']['categories'] = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data['series'][0]['data'] = $data_month;

        return $data;
    }

    private function getReviewQuantityByMonth($month)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        
        $currentYear = date("Y");
        $currentMonth = $currentYear."-".$month."-01";
        $nextMonth = date("Y-m-d", strtotime("+1 month", strtotime($currentMonth)));

        $queryBuilder->select('count(o.date_created)')
            ->from(Review::class, 'o')
            ->where('o.date_created >= :month')
            ->andWhere('o.date_created < :nextmonth')
            ->setParameter('month', $currentMonth)
            ->setParameter('nextmonth', $nextMonth);

        return $queryBuilder->getQuery()->getResult();
    }

    private function getReviewQuantityByWeek($day)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        
        $currentMonth = date("Y-m");
        $currentWeekDay = $currentMonth."-".$day;
        if ($day == 22){
            $currentMonth = date("Y-m")."-01";
            $currentMonth = strtotime($currentMonth);
            $nextWeekDay = date("Y-m-d", strtotime("+1 month", $currentMonth));
        }
        else $nextWeekDay = $currentMonth."-".($day+7);

        $queryBuilder->select('count(o.date_created)')
            ->from(Review::class, 'o')
            ->where('o.date_created >= :weekday')
            ->andWhere('o.date_created < :nextweek')
            ->setParameter('weekday', $currentWeekDay)
            ->setParameter('nextweek', $nextWeekDay);

        return $queryBuilder->getQuery()->getResult();
    }

    // user chart 
    public function getUserStatistic($type)
    {
        if ($type == 1)
            return $this->makeUserDataChartByMonth();
        else return $this->makeUserDataChartByYear();
    }

    private function makeUserDataChartByMonth()
    {
        $data_week[0] = $this->getUserQuantityByWeek(1)[0][1];
        $data_week[1] = $this->getUserQuantityByWeek(8)[0][1];
        $data_week[2] = $this->getUserQuantityByWeek(15)[0][1];
        $data_week[3] = $this->getUserQuantityByWeek(22)[0][1];

        $data = $this->getPreConfigChart('users');
        $data['subtitle']['text'] = 'New User By Month';
        $data['xAxis']['categories'] = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        $data['series'][0]['data'] = $data_week;

        return $data;
    }

    private function makeUserDataChartByYear()
    {
        for ($i=0; $i < 12 ; $i++) { 
            $data_month[$i] = $this->getUserQuantityByMonth($i + 1)[0][1];
        }

        $data = $this->getPreConfigChart('new users');
        $data['subtitle']['text'] = 'New User By Year';
        $data['xAxis']['categories'] = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data['series'][0]['data'] = $data_month;

        return $data;
    }

    private function getUserQuantityByMonth($month)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        
        $currentYear = date("Y");
        $currentMonth = $currentYear."-".$month."-01";
        $nextMonth = date("Y-m-d", strtotime("+1 month", strtotime($currentMonth)));

        $queryBuilder->select('count(o.date_created)')
            ->from(User::class, 'o')
            ->where('o.date_created >= :month')
            ->andWhere('o.date_created < :nextmonth')
            ->setParameter('month', $currentMonth)
            ->setParameter('nextmonth', $nextMonth);

        return $queryBuilder->getQuery()->getResult();
    }

    private function getUserQuantityByWeek($day)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        
        $currentMonth = date("Y-m");
        $currentWeekDay = $currentMonth."-".$day;
        if ($day == 22){
            $currentMonth = date("Y-m")."-01";
            $currentMonth = strtotime($currentMonth);
            $nextWeekDay = date("Y-m-d", strtotime("+1 month", $currentMonth));
        }
        else $nextWeekDay = $currentMonth."-".($day+7);

        $queryBuilder->select('count(o.date_created)')
            ->from(User::class, 'o')
            ->where('o.date_created >= :weekday')
            ->andWhere('o.date_created < :nextweek')
            ->setParameter('weekday', $currentWeekDay)
            ->setParameter('nextweek', $nextWeekDay);

        return $queryBuilder->getQuery()->getResult();
    }

    // money chart 
    public function getMoneyStatistic($type)
    {
        if ($type == 1)
            return $this->makeMoneyDataChartByMonth();
        else return $this->makeMoneyDataChartByYear();
    }

    private function makeMoneyDataChartByMonth()
    {
        $data_week = [0, 0, 0, 0];
        $data_week[0]+= $this->getMoneyQuantityByWeek(1)[0][1];
        $data_week[1]+= $this->getMoneyQuantityByWeek(8)[0][1];
        $data_week[2]+= $this->getMoneyQuantityByWeek(15)[0][1];
        $data_week[3]+= $this->getMoneyQuantityByWeek(22)[0][1];

        $data = $this->getPreConfigChart('moneys');
        $data['subtitle']['text'] = 'Money By Month';

        $data['xAxis']['categories'] = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        $data['series'][0]['data'] = $data_week;

        return $data;
    }

    private function makeMoneyDataChartByYear()
    {
        for ($i=0; $i < 12 ; $i++) { 
            $data_month[$i] = 0;
            $data_month[$i]+= $this->getMoneyQuantityByMonth($i + 1)[0][1];
        }

        $data = $this->getPreConfigChart('moneys');
        $data['subtitle']['text'] = 'moneys By Year';
        $data['xAxis']['categories'] = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data['series'][0]['data'] = $data_month;

        return $data;
    }

    private function getMoneyQuantityByMonth($month)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        
        $currentYear = date("Y");
        $currentMonth = $currentYear."-".$month."-01";
        $nextMonth = date("Y-m-d", strtotime("+1 month", strtotime($currentMonth)));

        $queryBuilder->select('sum(o.cost)')
            ->from(Order::class, 'o')
            ->where('o.date_created >= :month')
            ->andWhere('o.date_created < :nextmonth')
            ->setParameter('month', $currentMonth)
            ->setParameter('nextmonth', $nextMonth);

        return $queryBuilder->getQuery()->getResult();
    }

    private function getMoneyQuantityByWeek($day)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        
        $currentMonth = date("Y-m");
        $currentWeekDay = $currentMonth."-".$day;
        if ($day == 22){
            $currentMonth = date("Y-m")."-01";
            $currentMonth = strtotime($currentMonth);
            $nextWeekDay = date("Y-m-d", strtotime("+1 month", $currentMonth));
        }
        else $nextWeekDay = $currentMonth."-".($day+7);

        $queryBuilder->select('sum(o.cost)')
            ->from(Order::class, 'o')
            ->where('o.date_created >= :weekday')
            ->andWhere('o.date_created < :nextweek')
            ->setParameter('weekday', $currentWeekDay)
            ->setParameter('nextweek', $nextWeekDay);

        return $queryBuilder->getQuery()->getResult();
    }

    private function getPreConfigChart($name)
    {
        $data['title']['text'] = $name;
        $data['yAxis']['title']['text'] = 'Number of '.$name;
        $data['legend']['layout'] = 'vertical';
        $data['legend']['align'] = 'right';
        $data['legend']['verticalAlign'] = 'middle';
        $data['plotOptions']['line']['dataLabels']['enabled'] = true;
        $data['series'][0]['name'] = $name.' Quantity';
        $data['series'][0]['showInLegend'] = false;
        if($name == 'moneys'){
            $data['yAxis']['labels']['format'] = '{value}$';
            $data['series'][0]['tooltip']['valueSuffix'] = '$';
        }

        return $data;
    }
}
    