<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Prize;
use App\Http\Requests\PrizeRequest;
use Illuminate\Http\Request;



class PrizesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
	{
		// Calculate the sum of all prize probabilities
		$totalProbability = Prize::sum('probability');

		// Calculate the remaining probability that can still be added to reach 100%
		$remainingProbability = 100 - $totalProbability;

		// Retrieve all prizes
		$prizes = Prize::all();

		// Get the prize titles and probabilities for the chart
		$prizeTitles = $prizes->pluck('title');        // Array of titles
		$probabilities = $prizes->pluck('probability'); // Array of probabilities

		// Pass the data to the view
		return view('prizes.index', [
			'prizes' => $prizes,
			'totalProbability' => $totalProbability,
			'remainingProbability' => $remainingProbability,
			'prizeTitles' => $prizeTitles,
			'probabilities' => $probabilities
		]);
	}

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        // Calculate the sum of all prize probabilities
        $totalProbability = Prize::sum('probability');
    
        // Calculate the remaining probability that can still be added to reach 100%
        $remainingProbability = 100 - $totalProbability;
    
        return view('prizes.create', [
            'totalProbability' => $totalProbability,
            'remainingProbability' => $remainingProbability,
        ]);
    }
    


    /**
     * Store a newly created resource in storage.
     *
     * @param  PrizeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PrizeRequest $request)
    {
        // Calculate the total probability by adding the current prize's probability
        $totalProbability = Prize::sum('probability') + floatval($request->input('probability'));
        
        // Calculate the remaining probability that can still be added
        $remainingProbability = 100 - Prize::sum('probability');
    
        // Check if the total probability exceeds 100
    if ($totalProbability > 100) {
        // Return the error message with the remaining probability
        return back()->withErrors([
            'probability' => "The probability field must not be greater then {$remainingProbability}."
        ])->withInput();
    }

    
        // Create the new prize and save it
        $prize = new Prize;
        $prize->title = $request->input('title');
        $prize->probability = floatval($request->input('probability'));
        $prize->save();
    
        return to_route('prizes.index');
    }
    

    public function edit($id)
    {
        $prize = Prize::findOrFail($id);
    
        // Calculate the sum of all prize probabilities except the one being edited
        $totalProbability = Prize::where('id', '!=', $id)->sum('probability');
    
        // Calculate the remaining probability that can still be added to reach 100%
        $remainingProbability = 100 - $totalProbability;
    
        return view('prizes.edit', [
            'prize' => $prize,
            'totalProbability' => $totalProbability,
            'remainingProbability' => $remainingProbability,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PrizeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    // Update Method
    public function update(PrizeRequest $request, $id)
    {
        // Find the prize being updated
        $prize = Prize::findOrFail($id);
    
        // Calculate the total probability excluding the current prize
        $totalProbability = Prize::where('id', '!=', $id)->sum('probability') + floatval($request->input('probability'));
    
        // Check if the total probability exceeds 100
        if ($totalProbability > 100) {
            $remainingProbability = 100 - Prize::where('id', '!=', $id)->sum('probability');
            // Display the remaining probability and show it in the error message
            return back()->withErrors(['probability' => 'The probability field must not be greater then' . $remainingProbability ])->withInput();
        }
    
        // Update the prize with new values
        $prize->title = $request->input('title');
        $prize->probability = floatval($request->input('probability'));
        $prize->save();
    
        return to_route('prizes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $prize = Prize::findOrFail($id);
        $prize->delete();

        return to_route('prizes.index');
    }


	public function simulate(Request $request)
	{
		// Number of prizes the user wants to distribute
		$numberOfPrizes = $request->input('number_of_prizes');

		// Retrieve all prizes from the database
		$prizes = Prize::all();

		// Initialize an array to track the number of times each prize is awarded
		$distributedPrizes = [];

		// Simulate the prize distribution process over all participants
		for ($i = 0; $i < $numberOfPrizes; $i++) {
			// Generate a random number between 0 and 100
			$randomNumber = rand(0, 100);

			// Check which prize this participant wins based on the probability ranges
			$currentProbability = 0;
			foreach ($prizes as $prize) {
				$currentProbability += $prize->probability;

				// If the random number falls within the probability range, award the prize
				if ($randomNumber < $currentProbability) {
					// Track how many times this prize has been awarded
					if (!isset($distributedPrizes[$prize->id])) {
						// Initialize the prize award count if not set
						$distributedPrizes[$prize->id] = [
							'title' => $prize->title,
							'awarded' => 0
						];
					}

					// Increment the awarded prize count
					$distributedPrizes[$prize->id]['awarded']++;
					break;
				}
			}
		}

		// Store the result of the simulation in the session
		session(['distributedPrizes' => $distributedPrizes]);

		// Return to the index view with the updated data
		return redirect()->route('prizes.index')->with('distributedPrizes', $distributedPrizes);
	}




    public function reset()
    {
        // TODO : Write logic here
		session()->forget('distributedPrizes');
        return to_route('prizes.index');
    }
}
