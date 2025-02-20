<?php
namespace App\Http\Traits;

use App\Http\Resources\CarResourse;
use App\Models\Bank;
use App\Models\BankOffer;
use App\Models\Car;
use App\Models\Offer;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Http;
use Illuminate\Validation\Rule;


trait Calculations{

    /*
        -- some Notes
            - administrative_fees => الرسوم الادارية
            - first batch  الدفعة الأولى
            - last batch الدفعة الأخيرة
            - insurance_percentage => التامين
            - installment => مدة التقسيط بالسنوات
            - benefir =>   الفائدة
            - advance المقدم
            - firstBatchIncludeAdministrativeFees =>  الدفعة الأولى تشمل الرسوم الإدارية
    */
// If accept_with_other_bank==1
    public function checkBankOffer($bankId,$sectorId,$brandId,$advance,$installment,$last_patch){
        $today = Carbon::now()->format('Y-m-d');
        return collect(DB::select("SELECT
            banks.id as bank_id,
            bank_offers.id as bank_offer_id,
            banks.name_".getLocale()." as bank_name,
            bank_offers.from as period_from,
            bank_offers.to as period_to,
            bank_offer_brand.brand_id as brand_id,
            bank_offer_sector.*
        from banks
            RIGHT JOIN
                bank_offers on banks.id = bank_offers.bank_id
            JOIN
                bank_offer_brand on bank_offer_brand.bank_offer_id = bank_offers.id
            JOIN
                bank_offer_sector on bank_offer_sector.bank_offer_id = bank_offers.id

            WHERE
                bank_offers.to > '".$today."'
            AND
                bank_offers.from <= '".$today."'
            AND
                bank_offer_sector.advance = ".$advance."
            AND
                bank_offer_sector.installment = ".$installment."
            AND
                bank_offer_sector.Last_patch = ".$last_patch."
            AND
                bank_offer_brand.brand_id = ".$brandId."
            AND
                bank_offer_sector.sector_id = ".$sectorId."

        "));


    }

    public function checkBankOffersec($bankId,$sectorId,$brandId,$advance,$installment){
        $today = Carbon::now()->format('Y-m-d');
        return collect(DB::select("SELECT
            banks.id as bank_id,
            bank_offers.id as bank_offer_id,
            banks.name_".getLocale()." as bank_name,
            bank_offers.from as period_from,
            bank_offers.to as period_to,
            bank_offer_brand.brand_id as brand_id,
            bank_offer_sector.*
        from banks
            RIGHT JOIN
                bank_offers on banks.id = bank_offers.bank_id
            JOIN
                bank_offer_brand on bank_offer_brand.bank_offer_id = bank_offers.id
            JOIN
                bank_offer_sector on bank_offer_sector.bank_offer_id = bank_offers.id

            WHERE
                bank_offers.to > '".$today."'
            AND
                bank_offers.from <= '".$today."'
            AND
                bank_offer_brand.brand_id = ".$brandId."
            AND
                banks.id = ".$bankId."
            AND
                bank_offer_sector.sector_id = ".$sectorId."
            AND
                bank_offer_sector.advance = ".$advance."
            AND
                bank_offer_sector.installment = ".$installment."
        "));

    }

    public function calculateInstallmentscar($request)
    {


        $bankOffer=null;
        $AllAvailableOffers=[];
         $salary_after_plus=($request->salary + ($request->support_price ?? 0));
        $car = Car::where('model_id', $request->model)
        ->where('brand_id', request('brand'))
        ->where('year', request('year'))
        ->where('gear_shifter',request('gear_shifter'))
        ->where('category_id',request('category'))
        ->first()??$request->car;
            if($car->have_discount==1){
                                 $car->discount_price = $car->discount_price+($car->discount_price * (settings()->getSettings('percentage_profit_for_car')/100)) ;

                }else{
                                 $car->price = $car->price+($car->price * (settings()->getSettings('percentage_profit_for_car')/100)) ;

                }
        $bank = Bank::find($request->bank);

        if($salary_after_plus < $bank->min_salary ){
            return $AllAvailableOffers;
        }

        else{
              if($salary_after_plus >= $bank->min_salary && $request->salary < $bank->max_salary){
            $deduction_percentage=$bank->Deduction_rate_without_mortgage_min;
            if($request->department_loan==1){

                if($request->department_loan_support==1){
                $deduction_percentage=$bank->Deduction_rate_with_support_mortgage_min;
                $deduction = (($request->salary+$request->support_price) ) * ($deduction_percentage / 100) - $request->Monthly_cometment;
                }else{
                $deduction_percentage=$bank->Deduction_rate_with_mortgage_min;
                $deduction = ($request->salary ) * ($deduction_percentage / 100) - $request->Monthly_cometment;

                }
            }else{
                $deduction = ($request->salary ) * ($deduction_percentage / 100) - $request->Monthly_cometment;

            }


        }
        elseif($salary_after_plus >= $bank->max_salary){
            $deduction_percentage=$bank->Deduction_rate_without_mortgage_max;
            if($request->department_loan==1){

                if($request->department_loan_support==1){
                $deduction_percentage=$bank->Deduction_rate_with_support_mortgage_max;
                $deduction = (($request->salary+$request->support_price) ) * ($deduction_percentage / 100) - $request->Monthly_cometment;
                }else{
                $deduction_percentage=$bank->Deduction_rate_with_mortgage_max;
                $deduction = ($request->salary ) * ($deduction_percentage / 100) - $request->Monthly_cometment;

                }
            }else{
                $deduction = ($request->salary ) * ($deduction_percentage / 100) - $request->Monthly_cometment;

            }


        }
        else{
          return $AllAvailableOffers;
        }

        if($car){
        $carDetails = CarResourse::make($car)->resolve();
        $brandId = $carDetails['brand']['id'];
        $sectorBenefit = null;
        $sectorSupport = null;
        $sectorAdvance = null;
        $sectorAdministrative_fees = null;
        $sectorInstallment = null;
        $bankOffer=[];
        $bankOffers = $this->checkBankOffer($request->bank,$request->sector,$carDetails['brand']['id'],$request->first_batch,$request->installment,$request->last_batch);

        foreach($bankOffers as $bankofferr){
            $bankdata=Bank::find($bankofferr->bank_id);

            if($bank->id==$bankofferr->bank_id){
             array_push($bankOffer,$bankofferr);
            }
            if($bankdata->accept_from_other_banks==1){
                if($bankdata->id!=$bank->id){
                    array_push($bankOffer,$bankofferr);
                }

            }
            }

        // else{
        //  $bankOffer = $this->checkBankOffersec($request->bank,$request->sector,$carDetails['brand']['id'],$request->first_batch,$request->installment);
        //  }
        }

         // $bankOffer = $this->checkBankOffer($request->bank,$request->sector,$brandId);
        if($bankOffer !=null){
            foreach ($bankOffer as $offer) {
            $sectorBenefit = $offer->benefit/100;
            $sectorSupport = $offer->support/100;
            $sectorAdvance = $offer->advance/100;
            $sectorAdministrative_fees = $offer->administrative_fees/100;
            $sectorInstallment = $offer->installment ; //year
            $price =  $car->getPriceAfterVatAttribute();

            if($offer->support >= 100){
                $price = 0;
            }else{
                $price=$price-($price * $sectorSupport);
            }
             $parameters = [
                'r-last_batch'=>$request->last_batch,
                'r-first_batch'=>$request->first_batch,
                'r-installment'=>$request->installment,
                'price'=>$price,
                'sectorInstallment'=>$sectorInstallment,
                'sectorBenefit'=>$sectorBenefit,
                'sectorAdministrative_fees'=>$sectorAdministrative_fees,
                'calculator_data' => json_encode($request->toArray()),
                'needed_fun'=>'calculator',
                'sex'=>$request->sex,
                'tax'=>settings()->getSettings('tax')/100,
                'insurance_female'=>settings()->getSettings('females_insurance'),
                'insurance_man'=>settings()->getSettings('males_insurance'),

             ];

          $response = $this->calc_function($parameters);
             if ($response) {
                $data = $response->getData(true); // Retrieve as associative array
                  $last_installment = $data['result']['last_installment'];
                $first_installment =$data['result']['first_installment'];
                $installment_years=$data['result']['installment_years'];
                $benefitPercentage =$data['result']['benefitPercentage'];
                $Adminstrativefeecost=$data['result']['Adminstrativefeecost'];
                $fundingAmount = $data['result']['fundingAmount'];
                $insurancePrice = $data['result']['insurancePrice'];
                $fundingAmountIncludeBenefit=$data['result']['fundingAmountIncludeBenefit'];
                $monthlyInstallment=$data['result']['monthlyInstallment'];

            } else {
                 abort(500, 'Failed to retrieve the PHP code from the external URL.');
            }

            $class='w-100';
            $param='';
              $result=[
                // 'lwest_monthly_installment' => $otherBannks['monthlyInstallment'] <$monthlyInstallment ? $otherBannks:null,
                'OfferName'=>BankOffer::find($offer->bank_offer_id)->toArray(),
                'monthly_installment' => number_format($monthlyInstallment, 2, '.', ','),
                'fundingAmount' => number_format(round($fundingAmount, 2), 2, '.', ','),
                'firs_installment'=>number_format(round($first_installment,2), 2, '.', ','),
                'years' => $installment_years,
                'last_installment'=> number_format(round($last_installment,2), 2, '.', ','),
                'sectorAdministrative_fees'=> number_format(round($Adminstrativefeecost), 2, '.', ','),
                'bank_offer_id'=>$offer->bank_offer_id,
                'car' => $carDetails,
                'price' =>  number_format($price, 2, '.', ',')
            ];

            if($monthlyInstallment>$deduction){
            return $AllAvailableOffers;
            }
            else{
            $AllAvailableOffers[] = $result;

            }

            }
            if($car==null){
                return 'Sorry Car Not Found';

            }
            else{
                return $AllAvailableOffers;

            }
         }else{
            return $AllAvailableOffers;

            // $sector = $bank->sectors()->find($request->sector_id)->pivot;
            // $sectorBenefit = $sector['benefit'];
            // $sectorSupport = $sector['support'];
            // $sectorAdvance = $sector['advance'];
            // $sectorAdministrative_fees = $sector['administrative_fees'];
            // $sectorInstallment = $sector['installment'];

        }

        }



    }


     public function calc_function($request)
    {

          $benefitPercentage=$request['sectorBenefit'];

        $first_installment=($request['r-first_batch']/100)*($request['price']);
        $last_installment=($request['r-last_batch']/100)*($request['price']);

        $installment_years=($request['sectorBenefit']<$request['sectorInstallment']?$request['sectorInstallment']:$request['r-installment'])??0;
        // $Adminstrativefeecost=($request['price']-$first_installment)*$request['sectorAdministrative_fees'];
        $pricefees=($request['price']-$first_installment)*$request['sectorAdministrative_fees'];
        $Adminstrativefeecost=$pricefees+($pricefees*$request['tax']);
        $fundingAmount=($request['price']-$first_installment)+$Adminstrativefeecost;

        if($request['sex']=='female'){
            $insurancePrice = ($request['price'] *  ($request['insurance_female'] / 100))*$request['r-installment'];
            }
            elseif($request['sex']=='male'){
            $insurancePrice = ($request['price'] * ($request['insurance_man'] / 100))*$request['r-installment'];
            }
            else{
                $insurancePrice = 0;
            }
            if ($benefitPercentage == 0)
            $fundingAmountIncludeBenefit =  $fundingAmount-$last_installment + $insurancePrice;
            else
            $fundingAmountIncludeBenefit = ( $fundingAmount *  $benefitPercentage * $installment_years) + $fundingAmount - $last_installment + $insurancePrice;

            $firstBatchIncludeAdministrativeFees = $first_installment + $Adminstrativefeecost;

        if ($installment_years > 0) {
        $monthlyInstallment = $fundingAmountIncludeBenefit / ($installment_years * 12);
        } else {
            // Handle the case when $installment_years is zero, for example, by setting $monthlyInstallment to 0 or showing an error message
            $monthlyInstallment = 0; // or display a meaningful error message
        }


        $result=[
            'first_installment'=>$first_installment,
            'last_installment'=>$last_installment,
            'installment_years'=>$installment_years,
            'benefitPercentage'=>$request['sectorBenefit'],
            'Adminstrativefeecost'=>$Adminstrativefeecost,
            'fundingAmount'=> $fundingAmount,
            'insurancePrice'=> $insurancePrice,
            'fundingAmountIncludeBenefit'=>$fundingAmountIncludeBenefit,
            'monthlyInstallment'=>$monthlyInstallment,
        ];

        return response()->json(['result' => $result]);

    }


    public function calculateByAmount($request)
    {
        $insurance = 0.03;

        $request->validate([
            'bank' => ['bail', 'required', 'exists:banks,id'],
            'sector' => ['bail', 'required', Rule::exists('bank_sector','sector_id')->where('bank_id',$request->bank)],
            'installment_amount' => ['bail', 'required', 'integer', 'min:1' ],
            'last_batch' => ['bail', 'required'],
            'years' => ['bail', 'required', 'integer', 'min:1'],
            'first_batch' => ['bail', 'required']
        ]);

        $bank = Bank::findOrFail($request->bank);
        $bankSectorPivotData = $bank->sectors->where('id', $request['sector'])->first()->pivot;
        $benefit = $bankSectorPivotData->benefit / 100;
        $support = $bankSectorPivotData->support > 0 ? ($bankSectorPivotData->support - 100) / 100 : 0;

        $request->validate([
            // 'years' => ['lte:' . $bankSectorPivotData->installment],
            // 'first_batch' => [ 'gte:' . $bankSectorPivotData->advance],
        ]);

        $maxCarPrice = ( ( $request['installment_amount'] * $request['years'] * 12 ) + $request['first_batch'] + $request['last_batch'] + ( $request['first_batch'] * $request['years'] * $benefit ) )
        /
        ( ( 1 + ($insurance * $request['years']) ) + ( $benefit * $request['years'] ) + $support);
        $maxCarPrice = ceil($maxCarPrice);

        $applicableCars = Car::select( Car::$carCardColumns )->where('price', '<=', $maxCarPrice)->orderByDesc('price')->get();
        $applicableCars = $applicableCars->filter(function($car) use($maxCarPrice,$applicableCars){
            return $car->price_after_vat <= $maxCarPrice;
        });

        return [
            "maxCarPrice" => $maxCarPrice,
            "applicableCars" => $applicableCars->values()
        ];
    }




}
