<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\JudgeRepository;


#[ORM\Entity(repositoryClass: JudgeRepository::class)]
class Judge extends User
{

 



   
}
