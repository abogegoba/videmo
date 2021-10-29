<?php


namespace App\Business\Services;


use App\Business\Interfaces\Gateways\Criteria\JobTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\PrefectureSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate\CompanyRecruitingCreateStoreInputPort;
use App\Domain\Entities\Company;
use App\Domain\Entities\JobApplication;
use App\Domain\Entities\JobType;
use App\Domain\Entities\Prefecture;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

trait UseCreateJobApplicationTrait
{
    /**
     * @param Company $company
     * @param $inputPort
     * @return JobApplication
     * @throws FatalBusinessException
     */
    protected function createJobApplication(Company $company, $inputPort): JobApplication
    {
        $jobApplication = new JobApplication();
        Data::mappingToObject($inputPort, $jobApplication);
        $jobApplication->setCompany($company);
        $jobApplication->setRecruitmentJobType($this->getJobTypeById($inputPort->jobType));
        $area1 = $this->getPrefectureById($inputPort->area1);
        $area2 = $this->getPrefectureById($inputPort->area2);
        $area3 = $this->getPrefectureById($inputPort->area3);
        $area4 = $this->getPrefectureById($inputPort->area4);
        $area5 = $this->getPrefectureById($inputPort->area5);
        $area6 = $this->getPrefectureById($inputPort->area6);
        $area7 = $this->getPrefectureById($inputPort->area7);
        $area8 = $this->getPrefectureById($inputPort->area8);
        $area9 = $this->getPrefectureById($inputPort->area9);
        $area10 = $this->getPrefectureById($inputPort->area10);
        $jobApplication->setRecruitmentWorkLocations(array_filter([$area1, $area2, $area3, $area4, $area5, $area6, $area7, $area8, $area9, $area10]));

        return $jobApplication;
    }

    /**
     * @param Company $company
     * @param $inputPort
     * @return JobApplication
     * @throws FatalBusinessException
     */
    protected function updateJobApplication(Company $company, $inputPort): JobApplication
    {
        $jobApplication = $this->getSelectedJobApplication($inputPort);
        Data::mappingToObject($inputPort, $jobApplication);
        $jobApplication->setCompany($company);
        $jobApplication->setRecruitmentJobType($this->getJobTypeById($inputPort->jobType));
        $area1 = $this->getPrefectureById($inputPort->area1);
        $area2 = $this->getPrefectureById($inputPort->area2);
        $area3 = $this->getPrefectureById($inputPort->area3);
        $area4 = $this->getPrefectureById($inputPort->area4);
        $area5 = $this->getPrefectureById($inputPort->area5);
        $area6 = $this->getPrefectureById($inputPort->area6);
        $area7 = $this->getPrefectureById($inputPort->area7);
        $area8 = $this->getPrefectureById($inputPort->area8);
        $area9 = $this->getPrefectureById($inputPort->area9);
        $area10 = $this->getPrefectureById($inputPort->area10);
        $jobApplication->setRecruitmentWorkLocations(array_filter([$area1, $area2, $area3, $area4, $area5, $area6, $area7, $area8, $area9, $area10]));

        return $jobApplication;
    }

    /**
     * 職種を取得
     *
     * @param int|null $jobTypeId
     * @return JobType|null
     * @throws FatalBusinessException
     */
    private function getJobTypeById(?int $jobTypeId): ?JobType
    {
        $jobType = null;
        if (!empty($jobTypeId)) {
            try {
                $jobType = $this->jobTypeRepository->findOneByCriteria(
                    CriteriaFactory::getInstance()->create(JobTypeSearchCriteria::class, GeneralExpressionBuilder::class, ["id" => $jobTypeId])
                );
            } catch (ObjectNotFoundException $e) {
                throw new FatalBusinessException("select_target_not_found");
            }
        }
        return $jobType;
    }

    /**
     * 都道府県を取得
     *
     * @param int|null $prefectureId
     * @return Prefecture|null
     * @throws FatalBusinessException
     */
    private function getPrefectureById(?int $prefectureId): ?Prefecture
    {
        $prefecture = null;
        if (!empty($prefectureId)) {
            try {
                $prefecture = $this->prefectureRepository->findOneByCriteria(
                    CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class, ["id" => $prefectureId])
                );
            } catch (ObjectNotFoundException $e) {
                throw new FatalBusinessException("select_target_not_found");
            }
        }
        return $prefecture;
    }
}