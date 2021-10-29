<?php


namespace App\Business\UseCases\Admin;


use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\JobApplicationRepository;
use App\Business\Interfaces\Gateways\Repositories\JobTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Interactors\Admin\JobApplicationDelete\JobApplicationDeleteInputPort;
use App\Business\Interfaces\Interactors\Admin\JobApplicationDelete\JobApplicationDeleteInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationDelete\JobApplicationDeleteOutputPort;
use App\Business\Services\UseSelectedJobApplicationTrait;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

class JobApplicationDeleteUseCase implements JobApplicationDeleteInteractor
{
    use UseSelectedJobApplicationTrait;
    /**
     * @var JobApplicationRepository
     */
    private $jobApplicationRepository;

    /**
     * @var JobTypeRepository
     */
    private $jobTypeRepository;

    /**
     * @var PrefectureRepository
     */
    private $prefectureRepository;

    /**
     * @var companyAccountRepository
     */
    private $companyAccountRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * CompanyRecruitingEditUseCase constructor.
     * @param JobApplicationRepository $jobApplicationRepository
     * @param JobTypeRepository $jobTypeRepository
     * @param PrefectureRepository $prefectureRepository
     */
    public function __construct(
        JobApplicationRepository $jobApplicationRepository,
        JobTypeRepository $jobTypeRepository,
        PrefectureRepository $prefectureRepository,
        CompanyAccountRepository $companyAccountRepository,
        CompanyRepository $companyRepository
    )
    {
        $this->jobApplicationRepository = $jobApplicationRepository;
        $this->jobTypeRepository = $jobTypeRepository;
        $this->prefectureRepository = $prefectureRepository;
        $this->companyAccountRepository = $companyAccountRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * 削除する
     *
     * @param JobApplicationDeleteInputPort $inputPort
     * @param JobApplicationDeleteOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function destroy(JobApplicationDeleteInputPort $inputPort, JobApplicationDeleteOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        //削除対象の存在を確認
        $deletedJobApplication = $this->getSelectedJobApplication($inputPort);

        // 求人情報に紐づく勤務地を削除
        $recruitmentWorkLocations = $deletedJobApplication->getRecruitmentWorkLocations();

        if (count($recruitmentWorkLocations) > 0){
            foreach ($recruitmentWorkLocations as $recruitmentWorkLocation){
                $recruitmentWorkLocations->removeElement($recruitmentWorkLocation);
            }
        }
        $this->jobApplicationRepository->saveOrUpdate($deletedJobApplication,true);
        
        // 求人を削除
        $this->jobApplicationRepository->delete($deletedJobApplication);

        // ログ出力
        Log::infoOut();
    }
}