import React, {useContext, useEffect, useState} from 'react';
import Step from "./components/Step";



const Base = ({recipeSteps}) => {
    return (
        <div className="my-1 p-2">
            <div className="d-flex flex-column">
                {
                    recipeSteps.map((recipeStep, index)=>{
                   return  <Step key={recipeStep.id} index={index+1} recipeStep={recipeStep}   />
                })}


            </div>
        </div>
    );
}
export default Base;